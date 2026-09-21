<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(MenuItem::class)->whereNull('parent_id')->orderBy('order');
    }

    /**
     * Top-level items a visitor should actually see, for the named menu.
     *
     * An item survives three checks: the menu is active, the item is active, and
     * — when the item links to a CMS page — that page is not a Draft. Items that
     * do not map to a pages row (route-only links such as /services, anchors and
     * external URLs) are left alone.
     *
     * Returns an empty collection when the menu is missing or inactive.
     */
    public static function navigation(string $name = 'primary'): Collection
    {
        $menu = static::query()
            ->where('name', $name)
            ->where('is_active', true)
            ->first();

        if (! $menu) {
            return collect();
        }

        $items = $menu->items()->active()->get();

        $slugs = $items->map->pageSlug()->filter()->unique()->values();

        if ($slugs->isEmpty()) {
            return $items;
        }

        $pageStatus = Page::query()
            ->whereIn('slug', $slugs)
            ->pluck('is_active', 'slug');

        return $items->filter(function (MenuItem $item) use ($pageStatus) {
            $slug = $item->pageSlug();

            // No matching page row means the link is route-only, so it stays.
            return $slug === null || ! $pageStatus->has($slug) || (bool) $pageStatus[$slug];
        })->values();
    }
}
