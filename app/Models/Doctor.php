<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
     public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'doctor_id', 'user_id');
    }

    // also useful: direct favorites relationship
    public function favorites()
    {
        return $this->hasMany(Favourite::class, 'doctor_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
}

