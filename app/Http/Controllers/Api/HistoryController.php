<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveHistoryRequest;
use App\Repositories\Contracts\HistoryRepositoryInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class HistoryController extends Controller
{
    use ApiResponseTrait;

    protected HistoryRepositoryInterface $searchHistoryRepository;

    public function __construct(HistoryRepositoryInterface $searchHistoryRepository)
    {
        $this->searchHistoryRepository = $searchHistoryRepository;
    }

    /**
     * Fetch the authenticated user's search history.
     */
    public function index(): JsonResponse
    {
        $history = $this->searchHistoryRepository->getUserSearchHistory(Auth::id());

        if ($history->isEmpty()) {
            return $this->successResponse([], 'No search history found');
        }

        return $this->successResponse($history, 'Search history fetched successfully');
    }

    /**
     * Manually save a search entry (though it's automatically saved by DoctorController index).
     * This could be useful if search happens in a different flow.
     */
    public function store(SaveHistoryRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $history = $this->searchHistoryRepository->saveSearch(
            Auth::id(),
            $validated['search_term'],
            $validated['location'] ?? null,
            $validated['search_lat'] ?? null,
            $validated['search_long'] ?? null
        );

        return $this->successResponse($history, 'Search history saved successfully', 201);
    }
}