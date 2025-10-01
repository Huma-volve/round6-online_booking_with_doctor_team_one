<?php

namespace App\Repositories;

use App\Models\SocialAccoutns;
use App\Models\User;
use App\Repositories\Interfaces\SocialAuthInterface;
use Illuminate\Support\Str;

class FacebookAuthRepository implements SocialAuthInterface
{
    public function getSocialAccount($facebookUser)
    {
        return SocialAccoutns::where('provider', 'facebook')
            ->where('provider_id', $facebookUser->getId())
            ->first();
    }
    public function checkUserExists($facebookUser)
    {
        return User::where('email', $facebookUser->getEmail())->first();
    }
    public function createUser($facebookUser)
    {
        return User::create([
            'name' => $facebookUser->getName(),
            'email' => $facebookUser->getEmail(),
            'password' => bcrypt(Str::random(8)),
            'phone' => null,
            'role' => 'patient',
            'email_verified_at' => now(),
        ]);
    }
    public function createSocialAccount($facebookUser, $user)
    {
        SocialAccoutns::create([
            'user_id' => $user->id,
            'provider' => 'facebook',
            'provider_id' => $facebookUser->getId(),
        ]);
    }
}
