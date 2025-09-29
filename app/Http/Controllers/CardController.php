<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddCardRequest;
use App\Services\CardService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;

class CardController extends Controller
{
    protected $cardService;

    public function __construct(CardService $cardService)
    {
        $this->cardService = $cardService;
    }

    /**
     * Add a new card to the user's account.
     */
    public function store(AddCardRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();

            $card = $this->cardService->addCard(
                $user,
                $request->payment_method_id,
                $request->get('is_default', false)
            );

            return response()->json([
                'status'  => true,
                'message' => 'Card added successfully.',
                'data'    => $card,
            ], 201);
        } catch (ApiErrorException $e) {
            // Stripe error
            return response()->json([
                'status'  => false,
                'message' => 'Stripe error: ' . $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            // General error
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong while adding the card.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * List all saved cards for the authenticated user.
     */
    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();

            $cards = $this->cardService->listCards($user);

            return response()->json([
                'status' => true,
                'data'   => $cards,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to retrieve cards.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Set a specific card as default for the user.
     */
    public function setDefault($id): JsonResponse
    {
        try {
            $user = Auth::user();

            $updated = $this->cardService->setDefault($user, $id);

            if (!$updated) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Card not found or update failed.',
                ], 404);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Default card updated successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to set default card.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a saved card for the user.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $user = Auth::user();

            $deleted = $this->cardService->deleteCard($user, $id);

            if (!$deleted) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Card not found or already deleted.',
                ], 404);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Card deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to delete card.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
