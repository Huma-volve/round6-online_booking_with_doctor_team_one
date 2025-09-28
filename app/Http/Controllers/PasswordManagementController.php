<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;
use App\Models\User;
use App\Models\OTP;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordManagementController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        $user = User::where('email', $request->email)->first();
        $otp = rand(100000, 999999);
        // $otp = "000000";
        OTP::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'type' => 'password reset',
            'expires_at' => now()->addMinutes(10),
        ]);
        Mail::to($request->email)->send(new PasswordResetMail($otp));
        return response()->json(['message' => 'Password Reset OTP sent to your email']);
    }
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string',
        ]);
        $user = User::where('email', $request->email)->first();
        $otpRecord = OTP::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->where('type', 'password reset')
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()->first();
        if (!$otpRecord) {
            return response()->json(['error' => 'Invalid or expired OTP'], 400);
        }
        $otpRecord->update(['is_used' => true]);
        $resetToken = Str::random(64);
        $otpRecord->update(
            [
                'reset_token' => $resetToken,
                'reset_expires_at' => now()->addMinutes(15)
            ]
        );

        return response()->json([
            'message' => 'OTP verified successfully',
            'reset_token' => $resetToken
        ]);
    }
    public function  passwordReset(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'reset_token' => 'required|string',
        ]);
        $otpRecord = OTP::where('reset_token', $request->reset_token)
            ->where('reset_expires_at', '>', now())
            ->first();
        if (!$otpRecord) {
            return response()->json(['error' => 'Invalid or expired reset token'], 400);
        }
        $user = $otpRecord->user;
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        $otpRecord->update(['reset_token' => null]);

        return response()->json(['message' => 'Password reset successfully']);
    }
}
