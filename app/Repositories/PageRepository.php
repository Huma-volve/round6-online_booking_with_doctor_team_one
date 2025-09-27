<?php

namespace App\Repositories;

use App\Models\Page;
use App\Repositories\Interfaces\PageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class PageRepository implements PageRepositoryInterface
{
    /**
     * Cache duration in minutes.
     */
    private const CACHE_DURATION = 60;

    /**
     * Cache key prefix.
     */
    private const CACHE_PREFIX = 'page:';

    /**
     * Find a page by its type with caching.
     */
    public function findByType(string $type): ?Page
    {
        try {
            $cacheKey = self::CACHE_PREFIX . $type;

            return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($type) {
                return Page::ofType($type)->first();
            });
        } catch (Exception $e) {
            Log::error('Error finding page by type', [
                'type' => $type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Fallback without cache
            return Page::ofType($type)->first();
        }
    }

    /**
     * Get all pages with caching.
     */
    public function findAll(): Collection
    {
        try {
            $cacheKey = self::CACHE_PREFIX . 'all';

            return Cache::remember($cacheKey, self::CACHE_DURATION, function () {
                return Page::all();
            });
        } catch (Exception $e) {
            Log::error('Error finding all pages', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Fallback without cache
            return Page::all();
        }
    }

    /**
     * Create a new page.
     */
    public function create(array $data): Page
    {
        try {
            DB::beginTransaction();

            $page = Page::create($data);

            // Clear relevant caches
            $this->clearCache($data['type']);
            $this->clearCache('all');

            DB::commit();

            return $page;
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error creating page', [
                'data' => $data,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Update a page by type.
     */
    public function updateByType(string $type, array $data): bool
    {
        try {
            DB::beginTransaction();

            $page = Page::ofType($type)->first();

            if (!$page) {
                return false;
            }

            $updated = $page->update($data);

            if ($updated) {
                // Clear relevant caches
                $this->clearCache($type);
                $this->clearCache('all');
            }

            DB::commit();

            return $updated;
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error updating page by type', [
                'type' => $type,
                'data' => $data,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Delete a page by type.
     */
    public function deleteByType(string $type): bool
    {
        try {
            DB::beginTransaction();

            $page = Page::ofType($type)->first();

            if (!$page) {
                return false;
            }

            $deleted = $page->delete();

            if ($deleted) {
                // Clear relevant caches
                $this->clearCache($type);
                $this->clearCache('all');
            }

            DB::commit();

            return $deleted;
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error deleting page by type', [
                'type' => $type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Get privacy policy page.
     */
    public function getPrivacyPolicy(): ?Page
    {
        return $this->findByType('privacy_policy');
    }

    /**
     * Get terms and conditions page.
     */
    public function getTermsConditions(): ?Page
    {
        return $this->findByType('terms_conditions');
    }

    /**
     * Clear cache for a specific page type.
     */
    public function clearCache(string $type): void
    {
        try {
            $cacheKey = self::CACHE_PREFIX . $type;
            Cache::forget($cacheKey);
            Cache::forget(self::CACHE_PREFIX . 'all');
        } catch (Exception $e) {
            Log::error('Error clearing cache', [
                'type' => $type,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Clear all page caches.
     */
    public function clearAllCache(): void
    {
        try {
            $types = array_keys(Page::getAvailableTypes());

            foreach ($types as $type) {
                $this->clearCache($type);
            }

            Cache::forget(self::CACHE_PREFIX . 'all');
        } catch (Exception $e) {
            Log::error('Error clearing all caches', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
