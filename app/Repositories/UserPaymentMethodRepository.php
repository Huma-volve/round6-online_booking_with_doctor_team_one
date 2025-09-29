<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserPaymentMethod;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\UserPaymentMethodRepositoryInterface;

class UserPaymentMethodRepository implements UserPaymentMethodRepositoryInterface
{
    /**
     * Create a new payment method record.
     */
    public function create(array $data): UserPaymentMethod
    {
        return UserPaymentMethod::create($data);
    }

    /**
     * Get all cards for a given user.
     */
    public function getUserCards(User $user): Collection
    {
        return UserPaymentMethod::where('user_id', $user->id)->get();
    }

    /**
     * Find a card by ID and user.
     */
    public function findById(User $user, int $cardId): ?UserPaymentMethod
    {
        return UserPaymentMethod::where('user_id', $user->id)
            ->where('id', $cardId)
            ->first();
    }

    /**
     * Set a card as default, unmark others.
     */
    public function setDefaultCard(User $user, int $cardId): bool
    {
        // Unmark all other cards
        UserPaymentMethod::where('user_id', $user->id)
            ->update(['is_default' => false]);

        // Mark selected card
        return UserPaymentMethod::where('user_id', $user->id)
            ->where('id', $cardId)
            ->update(['is_default' => true]) > 0;
    }

    /**
     * Delete a saved card.
     */
    // public function delete(User $user, int $cardId): bool
    // {
    //     return UserPaymentMethod::where('user_id', $user->id)
    //         ->where('id', $cardId)
    //         ->delete() > 0;
    // }

    public function delete(User $user, int $cardId): bool
    {
        $card = UserPaymentMethod::where('user_id', $user->id)
            ->where('id', $cardId)
            ->first();

        if (!$card) {
            return false;
        }

        // Step 1: Detach from Stripe
        $paymentMethod = \Stripe\PaymentMethod::retrieve($card->payment_method_id);
        $paymentMethod->detach();

        // Step 2: Delete from DB
        return $card->delete();
    }
}
