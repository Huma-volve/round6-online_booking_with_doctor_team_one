<?php
namespace App\Repositories;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Interfaces\LoginRepositoryInterface;
class LoginRepository implements LoginRepositoryInterface
{
    public function findByEmail(string $email)
    {
        return User::where('email',$email)->first();

    }
    public function checkPassword($user,string $password):bool
    {
        return Hash::check($password, $user->password);
    }
    public function createToken($user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }
    public function findByPhone(string $phone)
    {
        return User::where('phone',$phone)->first();
    }
}