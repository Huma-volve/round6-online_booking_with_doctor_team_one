<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;
use Vonage\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Repositories\LoginRepositoryInterface;

class PhoneLoginController extends Controller
{
    protected LoginRepositoryInterface $loginRepo;
    public function __construct(LoginRepositoryInterface $loginRepo)
    {
        $this->loginRepo = $loginRepo;
    }
    public function PhoneLogin(Request $request)
    {
        $request->validate([
            'phone' => 'required',
        ]);
        $user = $this->loginRepo->findByPhone($request->phone);

        if (!$user) {
            return response()->json([
                'message' => 'Phone number not found'
            ], 404);
        }

        // $otp = rand(100000, 999999);
        $otp = "000000";
        $token = Str::uuid()->toString();
        Cache::put("otp_{$token}", [
            'phone' => $request->phone,
            'otp' => $otp,
        ], now()->addMinutes(5));

        $basic  = new Basic(env('VONAGE_API_KEY'), env('VONAGE_API_SECRET'));
        $client = new Client($basic);

        $response = $client->sms()->send(
            new SMS($request->phone, env('VONAGE_BRAND'), "Your OTP code is: {$otp}")
        );

        return response()->json([
            'message' => 'OTP sent successfully',
            'token'   => $token,
        ]);
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'otp' => 'required',
        ]);

        $data = Cache::get("otp_{$request->token}");
        if (!$data) {
            return response()->json(['message' => 'OTP expired or invalid token'], 400);
        }


        if ($data['otp'] == $request->otp) {
            $user = $this->loginRepo->findByPhone($data['phone']);
            $token = $this->loginRepo->createToken($user);
            return response()->json([
                'message' => 'Logged in successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                ],
                'token_type' => 'Bearer',
                'access_token' => $token,

            ]);
        }

        return response()->json(['message' => 'Invalid OTP'], 400);
    }
}
