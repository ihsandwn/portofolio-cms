<?php

namespace App\Models;

use App\Casts\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = ['menu_id', 'title', 'url', 'route', 'parent_id', 'order', 'icon', 'is_active'];

    protected $casts = [
        'title' => Translatable::class,
        'is_active' => 'boolean',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * The CMS page slug this item points at, or null when the item is not a
     * single-segment link to this site (anchors, mail links, external hosts and
     * nested paths such as /ai-lab/auth/{token} all resolve to null).
     *
     * Used to hide items whose page has been set to Draft in Admin > Pages.
     */
    public function pageSlug(): ?string
    {
        $url = trim((string) $this->url);

        if ($url === '' || str_starts_with($url, '#') || str_starts_with($url, 'mailto:') || str_starts_with($url, 'tel:')) {
            return null;
        }

        $host = parse_url($url, PHP_URL_HOST);
        if ($host !== null && $host !== parse_url((string) config('app.url'), PHP_URL_HOST)) {
            return null;
        }

        $path = trim((string) parse_url($url, PHP_URL_PATH), '/');

        // /p/{slug} is the generic CMS page route; strip the prefix before matching.
        if (str_starts_with($path, 'p/')) {
            $path = substr($path, 2);
        }

        if ($path === '' || str_contains($path, '/')) {
            return null;
        }

        return $path;
    }
}
