<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Page\StorePageRequest;
use App\Repositories\Interfaces\PageRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class PageController extends Controller
{
    public function __construct(
        private PageRepositoryInterface $pageRepository
    ) {}

    /**
     * Get privacy policy page.
     */
    public function privacyPolicy(): JsonResponse
    {
        try {
            $page = $this->pageRepository->getPrivacyPolicy();

            if (!$page) {
                return response()->json([
                    'success' => false,
                    'message' => 'Privacy policy page not found',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Privacy policy retrieved successfully',
                'data' => [
                    'id' => $page->id,
                    'title' => $page->title,
                    'content' => $page->content,
                    'type' => $page->type,
                    'type_readable' => $page->type_readable,
                    'created_at' => $page->created_at,
                    'updated_at' => $page->updated_at,
                ]
            ], 200);
        } catch (Exception $e) {
            Log::error('Error retrieving privacy policy', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the privacy policy',
                'data' => null
            ], 500);
        }
    }

    /**
     * Get terms and conditions page.
     */
    public function termsConditions(): JsonResponse
    {
        try {
            $page = $this->pageRepository->getTermsConditions();

            if (!$page) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terms and conditions page not found',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Terms and conditions retrieved successfully',
                'data' => [
                    'id' => $page->id,
                    'title' => $page->title,
                    'content' => $page->content,
                    'type' => $page->type,
                    'type_readable' => $page->type_readable,
                    'created_at' => $page->created_at,
                    'updated_at' => $page->updated_at,
                ]
            ], 200);
        } catch (Exception $e) {
            Log::error('Error retrieving terms and conditions', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the terms and conditions',
                'data' => null
            ], 500);
        }
    }

    /**
     * Get all pages (admin only).
     */
    public function index(): JsonResponse
    {
        try {
            // Check if user is admin
            // if (!Auth::check() || Auth::user()->role !== 'admin') {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Unauthorized access. Admin role required.',
            //         'data' => null
            //     ], 403);
            // }
            $pages = $this->pageRepository->findAll();

            return response()->json([
                'success' => true,
                'message' => 'Pages retrieved successfully',
                'data' => $pages->map(function ($page) {
                    return [
                        'id' => $page->id,
                        'title' => $page->title,
                        'content' => $page->content,
                        'type' => $page->type,
                        'type_readable' => $page->type_readable,
                        'created_at' => $page->created_at,
                        'updated_at' => $page->updated_at,
                    ];
                })
            ], 200);
        } catch (Exception $e) {
            Log::error('Error retrieving all pages', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving pages',
                'data' => null
            ], 500);
        }
    }

    /**
     * Create a new page (admin only).
     */
    public function store(StorePageRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            // Check if page type already exists
            $existingPage = $this->pageRepository->findByType($validatedData['type']);
            if ($existingPage) {
                return response()->json([
                    'success' => false,
                    'message' => 'A page of this type already exists',
                    'data' => null
                ], 409);
            }

            $page = $this->pageRepository->create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Page created successfully',
                'data' => [
                    'id' => $page->id,
                    'title' => $page->title,
                    'content' => $page->content,
                    'type' => $page->type,
                    'type_readable' => $page->type_readable,
                    'created_at' => $page->created_at,
                    'updated_at' => $page->updated_at,
                ]
            ], 201);
        } catch (Exception $e) {
            Log::error('Error creating page', [
                'request_data' => $request->all(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);


            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the page',
                'data' => null
            ], 500);
        }
    }

    /**
     * Update a page by type (admin only).
     */
    public function update(Request $request, string $type): JsonResponse
    {
        try {
            // Check if user is admin
            // if (!Auth::check() || Auth::user()->role !== 'admin') {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Unauthorized access. Admin role required.',
            //         'data' => null
            //     ], 403);
            // }

            $request->validate([
                'title' => 'required|string|max:255|min:3',
                'content' => 'required|string|min:50|max:50000',
            ]);

            $updated = $this->pageRepository->updateByType($type, $request->only(['title', 'content']));

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Page not found or could not be updated',
                    'data' => null
                ], 404);
            }

            $page = $this->pageRepository->findByType($type);

            return response()->json([
                'success' => true,
                'message' => 'Page updated successfully',
                'data' => [
                    'id' => $page->id,
                    'title' => $page->title,
                    'content' => $page->content,
                    'type' => $page->type,
                    'type_readable' => $page->type_readable,
                    'created_at' => $page->created_at,
                    'updated_at' => $page->updated_at,
                ]
            ], 200);
        } catch (Exception $e) {
            Log::error('Error updating page', [
                'type' => $type,
                'request_data' => $request->all(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the page',
                'data' => null
            ], 500);
        }
    }
}
