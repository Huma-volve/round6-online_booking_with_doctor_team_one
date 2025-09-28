<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserProfileRepositoryInterface
{
    /**
     * Update the profile information for a given user.
     *
     * @param  \App\Models\User  $user
     * @param  array  $data
     * @return \App\Models\User
     */
    public function updateProfile(User $user, array $data): User;
}
