<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

    use App\Casts\Translatable;
    use Illuminate\Database\Eloquent\Factories\HasFactory;

    class BlogPost extends Model
    {
        use HasFactory;
    
        protected $fillable = [
            'slug',
            'title',
            'excerpt',
            'content_blocks',
            'seo_meta',
            'author_id',
            'category_id',
            'published_at',
        ];
    
        protected $casts = [
            'title' => Translatable::class,
            'excerpt' => Translatable::class,
            'content_blocks' => 'array',
            'seo_meta' => 'array',
            'published_at' => 'datetime',
        ];
    
        public function author()
        {
            return $this->belongsTo(User::class, 'author_id');
        }

        // Accessor for localized content blocks
        public function getBlocksAttribute()
        {
            $locale = app()->getLocale();
            $blocks = $this->content_blocks;

            // Check if blocks are localized (keyed by locale)
            if (isset($blocks['en']) || isset($blocks['id'])) {
                return $blocks[$locale] ?? $blocks['en'] ?? [];
            }

            // Fallback for legacy non-localized blocks
            return $blocks;
        }
    
        public function scopePublished($query)
        {
            return $query->whereNotNull('published_at')->where('published_at', '<=', now());
        }
    }
