<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'search_term',
        'location',
        'search_lat',
        'search_long',
    ];

    protected $casts = [
        'search_lat' => 'decimal:7',
        'search_long' => 'decimal:7',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
