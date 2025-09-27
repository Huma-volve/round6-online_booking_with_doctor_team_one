<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Collection;

interface UserAddressRepositoryInterface
{
    /**
     * Get all addresses for a given user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Support\Collection
     */
    public function listForUser(User $user): Collection;

    /**
     * Create a new address for the given user.
     *
     * @param  \App\Models\User  $user
     * @param  array  $data
     * @return \App\Models\Address
     */
    public function createForUser(User $user, array $data): Address;

    /**
     * Update an existing address.
     *
     * @param  \App\Models\Address  $address
     * @param  array  $data
     * @return \App\Models\Address
     */
    public function update(Address $address, array $data): Address;

    /**
     * Delete an existing address.
     *
     * @param  \App\Models\Address  $address
     * @return bool
     */
    public function delete(Address $address): bool;

    /**
     * Set an address as the default for a given user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Address  $address
     * @return \App\Models\Address
     */
    public function setDefault(User $user, Address $address): Address;
}
