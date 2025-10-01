<?php

namespace App\Repositories\Contracts;

use App\Models\History;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface HistoryRepositoryInterface
{
    public function saveSearch(
        int $userId, 
        string $searchTerm, 
        ?string $location = null, 
        ?float $searchLat = null, 
        ?float $searchLong = null)
        : History;

    public function getUserSearchHistory(int $userId): LengthAwarePaginator;
}