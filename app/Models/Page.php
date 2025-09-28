<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;

class Page extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'content',
        'type',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope a query to only include pages of a given type.
     */
    public function scopeOfType(Builder $query, string $type): void
    {
        $query->where('type', $type);
    }

    /**
     * Scope a query to only include privacy policy pages.
     */
    public function scopePrivacyPolicy(Builder $query): void
    {
        $query->where('type', 'privacy_policy');
    }

    /**
     * Scope a query to only include terms and conditions pages.
     */
    public function scopeTermsConditions(Builder $query): void
    {
        $query->where('type', 'terms_conditions');
    }

    /**
     * Get the page type as a readable string.
     */
    protected function typeReadable(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->type) {
                'privacy_policy' => 'Privacy Policy',
                'terms_conditions' => 'Terms & Conditions',
                default => ucfirst(str_replace('_', ' ', $this->type))
            }
        );
    }

    /**
     * Get the available page types.
     */
    public static function getAvailableTypes(): array
    {
        return [
            'privacy_policy' => 'Privacy Policy',
            'terms_conditions' => 'Terms & Conditions',
        ];
    }
}
