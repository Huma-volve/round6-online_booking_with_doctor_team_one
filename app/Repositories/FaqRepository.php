<?php

namespace App\Repositories;

use App\Models\Faq;
use App\Repositories\Interfaces\FaqRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class FaqRepository implements FaqRepositoryInterface
{
    /**
     * Cache duration in minutes.
     */
    private const CACHE_DURATION = 60;

    /**
     * Cache key prefix.
     */
    private const CACHE_PREFIX = 'faq:';

    /**
     * Find an FAQ by ID with caching.
     */
    public function findById(int $id): ?Faq
    {
        try {
            $cacheKey = self::CACHE_PREFIX . 'id:' . $id;

            return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($id) {
                return Faq::find($id);
            });
        } catch (Exception $e) {
            Log::error('Error finding FAQ by ID', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Fallback without cache
            return Faq::find($id);
        }
    }

    /**
     * Get all FAQs with caching.
     */
    public function findAll(): Collection
    {
        try {
            $cacheKey = self::CACHE_PREFIX . 'all';

            return Cache::remember($cacheKey, self::CACHE_DURATION, function () {
                return Faq::ordered()->get();
            });
        } catch (Exception $e) {
            Log::error('Error finding all FAQs', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Fallback without cache
            return Faq::ordered()->get();
        }
    }

    /**
     * Get all active FAQs ordered by order field with caching.
     */
    public function getActiveOrdered(): Collection
    {
        try {
            $cacheKey = self::CACHE_PREFIX . 'active_ordered';

            return Cache::remember($cacheKey, self::CACHE_DURATION, function () {
                return Faq::activeOrdered()->get();
            });
        } catch (Exception $e) {
            Log::error('Error finding active ordered FAQs', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Fallback without cache
            return Faq::activeOrdered()->get();
        }
    }

    /**
     * Get all inactive FAQs with caching.
     */
    public function getInactive(): Collection
    {
        try {
            $cacheKey = self::CACHE_PREFIX . 'inactive';

            return Cache::remember($cacheKey, self::CACHE_DURATION, function () {
                return Faq::inactive()->ordered()->get();
            });
        } catch (Exception $e) {
            Log::error('Error finding inactive FAQs', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Fallback without cache
            return Faq::inactive()->ordered()->get();
        }
    }

    /**
     * Create a new FAQ.
     */
    public function create(array $data): Faq
    {
        try {
            DB::beginTransaction();

            // If no order is provided, get the next order
            if (!isset($data['order']) || empty($data['order'])) {
                $data['order'] = $this->getNextOrder();
            }

            $faq = Faq::create($data);

            // Clear relevant caches
            $this->clearCache();

            DB::commit();

            return $faq;
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error creating FAQ', [
                'data' => $data,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Update an FAQ by ID.
     */
    public function updateById(int $id, array $data): bool
    {
        try {
            DB::beginTransaction();

            $faq = Faq::find($id);

            if (!$faq) {
                return false;
            }

            $updated = $faq->update($data);

            if ($updated) {
                // Clear relevant caches
                $this->clearCache();
                Cache::forget(self::CACHE_PREFIX . 'id:' . $id);
            }

            DB::commit();

            return $updated;
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error updating FAQ by ID', [
                'id' => $id,
                'data' => $data,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Delete an FAQ by ID.
     */
    public function deleteById(int $id): bool
    {
        try {
            DB::beginTransaction();

            $faq = Faq::find($id);

            if (!$faq) {
                return false;
            }

            $deleted = $faq->delete();

            if ($deleted) {
                // Clear relevant caches
                $this->clearCache();
                Cache::forget(self::CACHE_PREFIX . 'id:' . $id);
            }

            DB::commit();

            return $deleted;
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error deleting FAQ by ID', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Get the next order number for new FAQs.
     */
    public function getNextOrder(): int
    {
        try {
            $maxOrder = Faq::max('order');
            return $maxOrder ? $maxOrder + 1 : 1;
        } catch (Exception $e) {
            Log::error('Error getting next order for FAQ', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return 1;
        }
    }

    /**
     * Clear cache for FAQs.
     */
    public function clearCache(): void
    {
        try {
            Cache::forget(self::CACHE_PREFIX . 'all');
            Cache::forget(self::CACHE_PREFIX . 'active_ordered');
            Cache::forget(self::CACHE_PREFIX . 'inactive');
        } catch (Exception $e) {
            Log::error('Error clearing FAQ cache', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Clear all FAQ caches.
     */
    public function clearAllCache(): void
    {
        try {
            $this->clearCache();

            // Clear individual FAQ caches (this is a simplified approach)
            // In a production environment, you might want to use cache tags
            $faqs = Faq::all();
            foreach ($faqs as $faq) {
                Cache::forget(self::CACHE_PREFIX . 'id:' . $faq->id);
            }
        } catch (Exception $e) {
            Log::error('Error clearing all FAQ caches', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
