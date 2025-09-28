<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Collection;
use App\Repositories\Interfaces\UserAddressRepositoryInterface;

class UserAddressRepository implements UserAddressRepositoryInterface
{
    /**
     * Get all addresses for a given user.
     */
    public function listForUser(User $user): Collection
    {
        return $user->addresses()->get();
    }

    /**
     * Create a new address for the given user.
     */
    public function createForUser(User $user, array $data): Address
    {
        // If this is set as default, reset other addresses
        if (!empty($data['is_default']) && $data['is_default'] === true) {
            $user->addresses()->update(['is_default' => false]);
        }

        return $user->addresses()->create($data);
    }

    /**
     * Update an existing address.
     */
    public function update(Address $address, array $data): Address
    {
        // If updating to default, reset others
        if (!empty($data['is_default']) && $data['is_default'] === true) {
            $address->user->addresses()->update(['is_default' => false]);
        }

        $address->update($data);

        return $address->fresh();
    }

    /**
     * Delete an existing address.
     */
    public function delete(Address $address): bool
    {
        return $address->delete();
    }

    /**
     * Set an address as the default for a given user.
     */
    public function setDefault(User $user, Address $address): Address
    {
        // Reset all to non-default
        $user->addresses()->update(['is_default' => false]);

        // Set chosen one as default
        $address->update(['is_default' => true]);

        return $address->fresh();
    }
}
