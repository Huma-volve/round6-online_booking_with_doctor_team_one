<?php

namespace App\Repositories;

use App\Repositories\Interfaces\ForgetPasswordInterface;
use App\Models\User;
use App\Models\OTP;
use Illuminate\Support\Facades\Hash;

class ForgetPasswordRepository implements ForgetPasswordInterface
{
    public function getUser($request)
    {
        return User::where('email', $request->email)->first();
    }
    public function createOTP($user, $otp)
    {
        OTP::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'type' => 'password reset',
            'expires_at' => now()->addMinutes(10),
        ]);
    }

    public function getotpRecord($user, $request)
    {
        return OTP::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->where('type', 'password reset')
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()->first();
    }
    public function updateotpRecord($otpRecord, $resetToken)
    {
        $otpRecord->update(
            [
                'is_used' => true,
                'reset_token' => $resetToken,
                'reset_expires_at' => now()->addMinutes(15)
            ]
        );
    }
    public function findotpRecord($request)
    {
        return OTP::where('reset_token', $request->reset_token)
            ->where('reset_expires_at', '>', now())
            ->first();
    }
    public function updatePassword($user, $request)
    {
        $user->update([
            'password' => Hash::make($request->password),
        ]);
    }
    public function updateResetToken($otpRecord)
    {
        $otpRecord->update(['reset_token' => null]);
    }
}
