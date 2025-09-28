<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterRepository implements RegisterRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create([
            'name'     => $data['FullName'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'phone'    => $data['phone'] ?? null,
        ]);
    }
}
