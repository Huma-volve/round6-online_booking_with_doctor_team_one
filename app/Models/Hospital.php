<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'latitude',
        'longitude',
        'rate',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'rate' => 'float',
    ];

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_hospital', 'hospital_id', 'doctor_id');
    }
}