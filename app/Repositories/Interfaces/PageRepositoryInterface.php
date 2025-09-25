<?php

namespace App\Repositories\Interfaces;

use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;

interface PageRepositoryInterface
{
    /**
     * Find a page by its type.
     */
    public function findByType(string $type): ?Page;

    /**
     * Get all pages.
     */
    public function findAll(): Collection;

    /**
     * Create a new page.
     */
    public function create(array $data): Page;

    /**
     * Update a page by type.
     */
    public function updateByType(string $type, array $data): bool;

    /**
     * Delete a page by type.
     */
    public function deleteByType(string $type): bool;

    /**
     * Get privacy policy page.
     */
    public function getPrivacyPolicy(): ?Page;

    /**
     * Get terms and conditions page.
     */
    public function getTermsConditions(): ?Page;

    /**
     * Clear cache for a specific page type.
     */
    public function clearCache(string $type): void;

    /**
     * Clear all page caches.
     */
    public function clearAllCache(): void;
}
