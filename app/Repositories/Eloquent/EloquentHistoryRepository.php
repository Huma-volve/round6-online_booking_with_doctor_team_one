<?php


namespace App\Repositories\Eloquent;

use App\Models\History;
use App\Repositories\Contracts\HistoryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EloquentHistoryRepository implements HistoryRepositoryInterface
{
    public function saveSearch(int $userId,
     string $searchTerm, 
     ?string $location = null, 
     ?float $searchLat = null, 
     ?float $searchLong = null
     ): History
    {
        return History::create([
            'user_id' => $userId,
            'search_term' => $searchTerm,
            'location' => $location,
            'search_lat' => $searchLat,
            'search_long' => $searchLong,
        ]);
    }

    public function getUserSearchHistory(int $userId): LengthAwarePaginator
    {
        return History::where('user_id', $userId)
                      ->orderByDesc('created_at')
                      ->paginate(10);
    }
}