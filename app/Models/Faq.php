<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;

class Faq extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'question',
        'answer',
        'order',
        'status',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope a query to only include active FAQs.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inactive FAQs.
     */
    public function scopeInactive(Builder $query): void
    {
        $query->where('status', 'inactive');
    }

    /**
     * Scope a query to order FAQs by order field.
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('order', 'asc');
    }

    /**
     * Scope a query to get FAQs ordered by status and order.
     */
    public function scopeActiveOrdered(Builder $query): void
    {
        $query->where('status', 'active')->orderBy('order', 'asc');
    }

    /**
     * Get the available status options.
     */
    public static function getStatusOptions(): array
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
        ];
    }

    /**
     * Get the status as a readable string.
     */
    protected function statusReadable(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->status) {
                'active' => 'Active',
                'inactive' => 'Inactive',
                default => ucfirst($this->status)
            }
        );
    }
}
