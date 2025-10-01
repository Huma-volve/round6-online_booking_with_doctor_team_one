<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\MajorRepositoryInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MajorController extends Controller
{

    use ApiResponseTrait;
    protected MajorRepositoryInterface $majorRepository;

    public function __construct(MajorRepositoryInterface $majorRepository)
    {
        $this->majorRepository = $majorRepository;
    }

    /**
     * Fetch a list of all specialists (Majors).
     */
    public function index(): JsonResponse
    {
        $majors = $this->majorRepository->getAll();

        if ($majors->isempty()){
            return $this->successResponse([], 'no majors found');
        }
        return $this->successResponse($majors, 'Majors retrieved successfully');
    }

    /**
     * Fetch a single specialist by slug.
     */
    public function show(string $slug): JsonResponse
    {
        $major = $this->majorRepository->findBySlug($slug);

        if (!$major) {
            return $this->errorResponse('Major not found', 404);;
        }

        return $this->successResponse($major, 'Majors retrieved successfully');
    }
}