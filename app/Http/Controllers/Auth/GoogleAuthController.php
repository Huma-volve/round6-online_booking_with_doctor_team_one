<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccoutns;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function handleGoogleToken(Request $request)
    {
        try {
            // Validate access token
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

            // Get user info from Google using access token
            $googleUser = Socialite::driver('google')->userFromToken($request->access_token);

            // Check if social account exists
            $socialAccount = SocialAccoutns::where('provider', 'google')
                ->where('provider_id', $googleUser->getId())
                ->first();

            if ($socialAccount) {
                // User exists with this Google account
                $user = $socialAccount->user;
            } else {
                // Check if user exists by email
                $user = User::where('email', $googleUser->getEmail())->first();

                if (!$user) {
                    // Create new user
                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'password' => bcrypt(Str::random(8)),
                        'phone' => null,
                        'role' => 'patient',
                        'email_verified_at' => now(),
                    ]);
                }

                // Create social account record
                SocialAccoutns::create([
                    'user_id' => $user->id,
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId(),
                ]);
            }

            // Create API token
            $token = $user->createToken('auth_token')->plainTextToken;

            // Return response
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
                'message' => 'Google authentication failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
