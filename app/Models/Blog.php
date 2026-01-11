<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\Translatable;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'category',
        'published_at',
        'user_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'title' => Translatable::class,
        'content' => Translatable::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }
}
