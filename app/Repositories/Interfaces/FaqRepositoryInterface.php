<?php

namespace App\Repositories\Interfaces;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Collection;

interface FaqRepositoryInterface
{
    /**
     * Find an FAQ by ID.
     */
    public function findById(int $id): ?Faq;

    /**
     * Get all FAQs.
     */
    public function findAll(): Collection;

    /**
     * Get all active FAQs ordered by order field.
     */
    public function getActiveOrdered(): Collection;

    /**
     * Get all inactive FAQs.
     */
    public function getInactive(): Collection;

    /**
     * Create a new FAQ.
     */
    public function create(array $data): Faq;

    /**
     * Update an FAQ by ID.
     */
    public function updateById(int $id, array $data): bool;

    /**
     * Delete an FAQ by ID.
     */
    public function deleteById(int $id): bool;

    /**
     * Get the next order number for new FAQs.
     */
    public function getNextOrder(): int;

    /**
     * Clear cache for FAQs.
     */
    public function clearCache(): void;

    /**
     * Clear all FAQ caches.
     */
    public function clearAllCache(): void;
}
