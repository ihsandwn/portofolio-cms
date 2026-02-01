<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'portfolio_id',
        'email',
        'token',
        'status',
        'expires_at',
        'ip_address',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}
