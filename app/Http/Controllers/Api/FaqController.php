<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Faq\StoreFaqRequest;
use App\Repositories\Interfaces\FaqRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class FaqController extends Controller
{
    public function __construct(
        private FaqRepositoryInterface $faqRepository
    ) {}

    /**
     * Get all active FAQs ordered by order field.
     */
    public function index(): JsonResponse
    {
        try {
            $faqs = $this->faqRepository->getActiveOrdered();

            return response()->json([
                'success' => true,
                'message' => 'FAQs retrieved successfully',
                'data' => $faqs->map(function ($faq) {
                    return [
                        'id' => $faq->id,
                        'question' => $faq->question,
                        'answer' => $faq->answer,
                        'order' => $faq->order,
                        'status' => $faq->status,
                        'status_readable' => $faq->status_readable,
                        'created_at' => $faq->created_at,
                        'updated_at' => $faq->updated_at,
                    ];
                })
            ], 200);
        } catch (Exception $e) {
            Log::error('Error retrieving FAQs', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving FAQs',
                'data' => null
            ], 500);
        }
    }

    /**
     * Get all FAQs (including inactive) for management purposes.
     */
    public function all(): JsonResponse
    {
        try {
            $faqs = $this->faqRepository->findAll();

            return response()->json([
                'success' => true,
                'message' => 'All FAQs retrieved successfully',
                'data' => $faqs->map(function ($faq) {
                    return [
                        'id' => $faq->id,
                        'question' => $faq->question,
                        'answer' => $faq->answer,
                        'order' => $faq->order,
                        'status' => $faq->status,
                        'status_readable' => $faq->status_readable,
                        'created_at' => $faq->created_at,
                        'updated_at' => $faq->updated_at,
                    ];
                })
            ], 200);
        } catch (Exception $e) {
            Log::error('Error retrieving all FAQs', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving all FAQs',
                'data' => null
            ], 500);
        }
    }

    /**
     * Get a specific FAQ by ID.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $faq = $this->faqRepository->findById($id);

            if (!$faq) {
                return response()->json([
                    'success' => false,
                    'message' => 'FAQ not found',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'FAQ retrieved successfully',
                'data' => [
                    'id' => $faq->id,
                    'question' => $faq->question,
                    'answer' => $faq->answer,
                    'order' => $faq->order,
                    'status' => $faq->status,
                    'status_readable' => $faq->status_readable,
                    'created_at' => $faq->created_at,
                    'updated_at' => $faq->updated_at,
                ]
            ], 200);
        } catch (Exception $e) {
            Log::error('Error retrieving FAQ', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the FAQ',
                'data' => null
            ], 500);
        }
    }

    /**
     * Create a new FAQ.
     */
    public function store(StoreFaqRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $faq = $this->faqRepository->create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'FAQ created successfully',
                'data' => [
                    'id' => $faq->id,
                    'question' => $faq->question,
                    'answer' => $faq->answer,
                    'order' => $faq->order,
                    'status' => $faq->status,
                    'status_readable' => $faq->status_readable,
                    'created_at' => $faq->created_at,
                    'updated_at' => $faq->updated_at,
                ]
            ], 201);
        } catch (Exception $e) {
            Log::error('Error creating FAQ', [
                'request_data' => $request->all(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the FAQ',
                'data' => null
            ], 500);
        }
    }

    /**
     * Update an existing FAQ.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'question' => 'sometimes|required|string|max:500|min:10',
                'answer' => 'sometimes|required|string|min:20|max:5000',
                'order' => 'sometimes|nullable|integer|min:1|max:9999',
                'status' => 'sometimes|nullable|string|in:active,inactive'
            ]);

            $updated = $this->faqRepository->updateById($id, $request->only(['question', 'answer', 'order', 'status']));

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'FAQ not found or could not be updated',
                    'data' => null
                ], 404);
            }

            $faq = $this->faqRepository->findById($id);

            return response()->json([
                'success' => true,
                'message' => 'FAQ updated successfully',
                'data' => [
                    'id' => $faq->id,
                    'question' => $faq->question,
                    'answer' => $faq->answer,
                    'order' => $faq->order,
                    'status' => $faq->status,
                    'status_readable' => $faq->status_readable,
                    'created_at' => $faq->created_at,
                    'updated_at' => $faq->updated_at,
                ]
            ], 200);
        } catch (Exception $e) {
            Log::error('Error updating FAQ', [
                'id' => $id,
                'request_data' => $request->all(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the FAQ',
                'data' => null
            ], 500);
        }
    }

    /**
     * Delete an FAQ.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->faqRepository->deleteById($id);

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'FAQ not found or could not be deleted',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'FAQ deleted successfully',
                'data' => null
            ], 200);
        } catch (Exception $e) {
            Log::error('Error deleting FAQ', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the FAQ',
                'data' => null
            ], 500);
        }
    }
}
