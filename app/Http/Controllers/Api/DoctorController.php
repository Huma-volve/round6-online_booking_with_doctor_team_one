<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorFilterRequest;
use App\Repositories\Contracts\DoctorRepositoryInterface;
use App\Repositories\Contracts\HistoryRepositoryInterface;
use App\Repositories\Contracts\MajorRepositoryInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class DoctorController extends Controller
{
    use ApiResponseTrait;

    protected DoctorRepositoryInterface $doctorRepository;
    protected HistoryRepositoryInterface $searchHistoryRepository; 

    public function __construct(
        DoctorRepositoryInterface $doctorRepository,
        HistoryRepositoryInterface $searchHistoryRepository
    ) {
        $this->doctorRepository = $doctorRepository;
        $this->searchHistoryRepository = $searchHistoryRepository;
    }

    /**
     * Fetch a list of doctors with various filters.
     * All filters should be combinable.
     */
    public function index(DoctorFilterRequest $request): JsonResponse
    {
        $filters = $request->validated();

        $doctors = $this->doctorRepository->getAllDoctors($filters);

        // Save search history if a user is authenticated and filters were applied
        if (Auth::check() && !empty(array_filter($filters))) {
            $searchTerm = '';
            if (isset($filters['name'])) $searchTerm .= "Dr. {$filters['name']}; ";
            if (isset($filters['specialist'])) $searchTerm .= "{$filters['specialist']} specialist; ";
            if (isset($filters['latitude']) && isset($filters['longitude'])){
                $searchTerm .= "Near lat:  {$filters['latitude']},
                long: {$filters['longitude']}";
                if (isset($filters['radius']))
                $searchTerm .= "within {$filters['radius']}km";
            }

            $location = $filters['location'] ?? null;
            $searchLat = $filters['latitude'] ?? null;
            $searchLong = $filters['longitude'] ?? null;

            // Only save if there's an actual search term or location
            if (!empty(trim($searchTerm, '; ')) || !empty($location)) {
                $this->searchHistoryRepository->saveSearch(
                    Auth::id(),
                    trim($searchTerm, '; '),
                    $location,
                    $searchLat,
                    $searchLong
                );
            }
        }

        if ($doctors->isEmpty()) {
            return $this->successResponse([], 'No doctors found');
        }
        return $this->successResponse($doctors, 'Doctors fetched successfully');
    }

    /**
     * Fetch a single doctor by ID.
     */
    public function show(int $id): JsonResponse
    {
        $doctor = $this->doctorRepository->findDoctor($id);

        if (!$doctor) {
            return $this->errorResponse('Doctor not found', 404);
        }

        return $this->successResponse($doctor, 'Doctor fetched successfully');
    }
}