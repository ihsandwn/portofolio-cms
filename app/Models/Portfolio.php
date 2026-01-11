<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\Translatable;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'client',
        'url',
        'repo_url',
        'type',
        'tech_stack',
        'case_study',
        'meta_data',
        'completed_at',
        'is_featured',
    ];

    protected $casts = [
        'tech_stack' => 'array',
        'meta_data' => 'array',
        'completed_at' => 'date',
        'is_featured' => 'boolean',
        'title' => Translatable::class,
        'description' => Translatable::class,
    ];

    public function getTechStackListAttribute()
    {
        return implode(', ', $this->tech_stack ?? []);
    }
}
