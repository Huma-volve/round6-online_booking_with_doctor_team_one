<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ForgetPasswordInterface;

class ForegetPasswordController extends Controller
{
    protected $forgetPasswordInterface;
    public function __construct(ForgetPasswordInterface $forgetPasswordInterface)
    {
        $this->forgetPasswordInterface = $forgetPasswordInterface;
    }
    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        $user = $this->forgetPasswordInterface->getUser($request);
        // $otp = rand(100000, 999999);
        $otp = "000000";
        $this->forgetPasswordInterface->createOTP($user, $otp);
        Mail::to($request->email)->send(new PasswordResetMail($otp));
        return response()->json(['message' => 'Password Reset OTP sent to your email']);
    }
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string',
        ]);
        $user = $user = $this->forgetPasswordInterface->getUser($request);
        $otpRecord = $this->forgetPasswordInterface->getotpRecord($user, $request);
        if (!$otpRecord) {
            return response()->json(['error' => 'Invalid or expired OTP'], 400);
        }
        $resetToken = Str::random(64);
        $this->forgetPasswordInterface->updateotpRecord($otpRecord, $resetToken);

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
        $otpRecord = $this->forgetPasswordInterface->findotpRecord($request);
        if (!$otpRecord) {
            return response()->json(['error' => 'Invalid or expired reset token'], 400);
        }
        $user = $otpRecord->user;
        $this->forgetPasswordInterface->updatePassword($user, $request);
        $this->forgetPasswordInterface->updateResetToken($otpRecord);

        return response()->json(['message' => 'Password reset successfully']);
    }
}
