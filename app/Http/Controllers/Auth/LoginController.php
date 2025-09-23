<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;
use Vonage\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Repositories\LoginRepositoryInterface;


class LoginController extends Controller
{
    protected LoginRepositoryInterface $loginRepo;
    public function __construct(LoginRepositoryInterface $loginRepo)
    {
        $this->loginRepo = $loginRepo;
    }
    public function EmailLogin(LoginRequest $request)
    {
        $data = $request->validated();

        $user = $this->loginRepo->findByEmail($data['email']);
        if (!$user || ! $this->loginRepo->checkPassword($user,$data['password'])) {
            return response()->json([
                'message' => 'Invalid email or password'
            ], 401);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
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
    public function PhoneLogin(Request $request)
    {
        $request->validate([
            'phone' => 'required',
        ]);

        // توليد OTP
        $otp = rand(100000, 999999);
        $token = Str::uuid()->toString();

        // حفظه في Cache لمدة 5 دقايق
        Cache::put("otp_{$token}", [
            'phone' => $request->phone,
            'otp' => $otp,
        ], now()->addMinutes(5));

        // ارسال SMS
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
