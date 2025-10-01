<?php

namespace App\Repositories;

use App\Models\SocialAccoutns;
use App\Models\User;
use App\Repositories\Interfaces\SocialAuthInterface;
use Illuminate\Support\Str;

class GoogleAuthRepository implements SocialAuthInterface
{
    public function getSocialAccount($googleUser)
    {
        return SocialAccoutns::where('provider', 'google')
            ->where('provider_id', $googleUser->getId())
            ->first();
    }
    public function checkUserExists($googleUser)
    {
        return User::where('email', $googleUser->getEmail())->first();
    }
    public function createUser($googleUser)
    {
        return User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'password' => bcrypt(Str::random(8)),
            'phone' => null,
            'role' => 'patient',
            'email_verified_at' => now(),
        ]);
    }
    public function createSocialAccount($googleUser, $user)
    {
        SocialAccoutns::create([
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_id' => $googleUser->getId(),
        ]);
    }
}
