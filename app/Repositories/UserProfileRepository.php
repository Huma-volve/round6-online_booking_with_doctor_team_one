<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserProfileRepositoryInterface;

class UserProfileRepository implements UserProfileRepositoryInterface
{
    /**
     * Update the profile information for a given user.
     *
     * @param  \App\Models\User  $user
     * @param  array  $data
     * @return \App\Models\User
     */
    public function updateProfile(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh();
    }
}
