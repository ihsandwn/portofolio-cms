<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
            'content_blocks' => 'array',
            'seo_meta' => 'array',
            'published_at' => 'datetime',
        ];
    
        public function author()
        {
            return $this->belongsTo(User::class, 'author_id');
        }
    
        public function scopePublished($query)
        {
            return $query->whereNotNull('published_at')->where('published_at', '<=', now());
        }
    }
