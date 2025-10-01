<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'price',
        'rating',
        'experience',
        'profile_picture',
        'bio',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'decimal:2',
    ];

    public function specialties()
    {
        return $this->belongsToMany(Specialty::class, 'doctor_specialty', 'doctor_id', 'specialty_id');
    }

    public function hospitals()
    {
        return $this->belongsToMany(Hospital::class, 'doctor_hospital', 'doctor_id', 'hospital_id');
    }

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