<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRegisterRequest;
use App\Repositories\RegisterRepositoryInterface;
use App\Http\Requests\LoginRequest;
use App\Repositories\LoginRepositoryInterface;

class EmailAuthController extends Controller
{
    protected RegisterRepositoryInterface $RegisterRepository;
    protected LoginRepositoryInterface $loginRepo;

    public function __construct(RegisterRepositoryInterface $RegisterRepository, LoginRepositoryInterface $loginRepo)
    {
        $this->RegisterRepository = $RegisterRepository;
        $this->loginRepo = $loginRepo;
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
    public function EmailLogin(LoginRequest $request)
    {
        $data = $request->validated();

        $user = $this->loginRepo->findByEmail($data['email']);
        if (!$user || ! $this->loginRepo->checkPassword($user, $data['password'])) {
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
}
