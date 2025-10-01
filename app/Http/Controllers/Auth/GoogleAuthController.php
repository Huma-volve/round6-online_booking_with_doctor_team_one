<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interfaces\SocialAuthInterface;

class GoogleAuthController extends Controller
{
    protected SocialAuthInterface $googleAuth;
    public function __construct(SocialAuthInterface $googleAuth)
    {
        $this->googleAuth = $googleAuth;
    }
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
            $socialAccount = $this->googleAuth->getSocialAccount($googleUser);

            if ($socialAccount) {
                // User exists with this Google account
                $user = $socialAccount->user;
            } else {
                // Check if user exists by email
                $user = $this->googleAuth->checkUserExists($googleUser);

                if (!$user) {
                    // Create new user
                    $user = $this->googleAuth->createUser($googleUser);
                }

                // Create social account record
                $this->googleAuth->createSocialAccount($googleUser,$user);
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
