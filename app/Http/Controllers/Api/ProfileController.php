<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\UserProfileRepositoryInterface;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    protected UserProfileRepositoryInterface $profileRepository;

    public function __construct(UserProfileRepositoryInterface $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }
   


    /**
     * Get the authenticated user's profile.
     */
    public function show(Request $request)
    {
        $user = $request->user();

        // Return full image URL if exists
        $user->profile_image_url = $user->profile_image
            ? Storage::url($user->profile_image)
            : null;

        return response()->json([
            'success' => true,
            'message' => 'Profile retrieved successfully',
            'data' => $user
        ], 200);
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();


        $data = $request->validated();

        // Handle profile image if uploaded

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $data['profile_image'] = $path;
        }

        // Update user via repository
        $updatedUser = $this->profileRepository->updateProfile($user, $data);


        // Return with real image URL

        $updatedUser->profile_image_url = $updatedUser->profile_image
            ? Storage::url($updatedUser->profile_image)
            : null;

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $updatedUser
        ], 200);
    }
}
