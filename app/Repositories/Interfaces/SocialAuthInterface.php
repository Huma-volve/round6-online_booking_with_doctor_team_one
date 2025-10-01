<?php
namespace App\Repositories\Interfaces;
interface SocialAuthInterface
{
    public function getSocialAccount($googleUser);
    public function checkUserExists($googleUser);
    public function createUser($googleUser);
    public function createSocialAccount($googleUser,$user);
}