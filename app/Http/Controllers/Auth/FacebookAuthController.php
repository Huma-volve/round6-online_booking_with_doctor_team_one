<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SocialAccoutns;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Catch_;

class FacebookAuthController extends Controller
{
    public function handleFacebookToken(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'access_token' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access token is required',
                    'errors' => $validator->errors()
                ], 400);
            }
            $facebookUser = Socialite::driver('facebook')->userFromToken($request->access_token);

            $socialAccount = SocialAccoutns::where('provider', 'facebook')
                ->where('provider_id', $facebookUser->getId())->first();

            if ($socialAccount) {
                $user = $socialAccount->user;
            } else {
                $user = User::where('email', $facebookUser->getEmail())->first();
                if (!$user) {
                    $user = User::create([
                        'name' => $facebookUser->getName(),
                        'email' => $facebookUser->getEmail(),
                        'password' => bcrypt(Str::random(8)),
                        'phone' => null,
                        'role' => 'patient',
                        'email_verified_at' => now(),
                    ]);
                }
                SocialAccoutns::create([
                    'user_id' => $user->id,
                    'provider' => 'facebook',
                    'provider_id' => $facebookUser->getId(),
                ]);
            }
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                    'email_verified_at' => $user->email_verified_at,
                ],
                'token' => $token,
                'token_type' => 'Bearer'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Facebook authentication failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
