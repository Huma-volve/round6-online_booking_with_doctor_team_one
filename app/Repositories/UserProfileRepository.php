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
        // Handle profile image upload if present
        if (isset($data['profile_image']) && $data['profile_image'] instanceof \Illuminate\Http\UploadedFile) {
            $path = $data['profile_image']->store('profile_images', 'public');
            $data['profile_image'] = $path;
        }

        // Update user record
        $user->update($data);

        return $user->fresh();
    }
}
