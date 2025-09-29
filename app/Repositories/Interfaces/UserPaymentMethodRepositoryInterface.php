<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use App\Models\UserPaymentMethod;
use Illuminate\Database\Eloquent\Collection;

interface UserPaymentMethodRepositoryInterface
{
    /**
     * Save a new payment method for the user.
     */
    public function create(array $data): UserPaymentMethod;

    /**
     * Get all saved cards for a given user.
     */
    public function getUserCards(User $user): Collection;

    /**
     * Set a card as the default for the user.
     */
    public function setDefaultCard(User $user, int $cardId): bool;

    /**
     * Delete a specific saved card.
     */
    public function delete(User $user, int $cardId): bool;

    /**
     * Find a single card by ID and user.
     */
    public function findById(User $user, int $cardId): ?UserPaymentMethod;
}
