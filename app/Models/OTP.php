<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    protected $fillable = [
        'user_id',
        'otp',
        'type',
        'is_used',
        'expires_at',
        'reset_token',
        'reset_expires_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
