<?php
namespace App\Repositories\Interfaces;
interface ForgetPasswordInterface
{
    public function getUser($request);
    public function createOTP($user,$otp);
    public function getotpRecord($user,$request);
    public function updateotpRecord($otpRecord,$restToken);
    public function findotpRecord($request);
    public function updatePassword($user,$request);
    public function updateResetToken($otpRecord);

}