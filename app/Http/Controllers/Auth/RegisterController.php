<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRegisterRequest;
use App\Http\Controllers\Controller;
use App\Repositories\RegisterRepositoryInterface;

class RegisterController extends Controller
{
    protected RegisterRepositoryInterface $RegisterRepository;
    public function __construct(RegisterRepositoryInterface $RegisterRepository)
    {
        $this->RegisterRepository = $RegisterRepository;
    }
    public function EmailRegister(StoreRegisterRequest $request)
    {
        $data = $request->validated();
        $user = $this->RegisterRepository->create($data);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'User registered successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
            'token_type' => 'Bearer',
            'access_token' => $token,
        ], 201);
    }
}
