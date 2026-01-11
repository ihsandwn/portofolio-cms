<?php

namespace App\Models;

use App\Casts\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'title', 'content', 'is_active'];

    protected $casts = [
        'title' => Translatable::class,
        'content' => Translatable::class,
        'is_active' => 'boolean',
    ];
}
