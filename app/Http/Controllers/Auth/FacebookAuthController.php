<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Catch_;
use App\Repositories\Interfaces\SocialAuthInterface;

class FacebookAuthController extends Controller
{
    protected SocialAuthInterface $facebookAuth;
    public function __construct(SocialAuthInterface $facebookAuth)
    {
        $this->facebookAuth = $facebookAuth;
    }
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

            $socialAccount = $this->facebookAuth->getSocialAccount($facebookUser);

            if ($socialAccount) {
                $user = $socialAccount->user;
            } else {
                $user = $this->facebookAuth->checkUserExists($facebookUser);
                if (!$user) {
                    $user = $this->facebookAuth->createUser($facebookUser);
                }
                $this->facebookAuth->createSocialAccount($facebookUser, $user);
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
