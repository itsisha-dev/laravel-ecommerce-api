<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Responses\ApiResponse;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function register(RegisterRequest $request)
    {
        try {
            $result = $this->authService->register($request->validated());
            return ApiResponse::success($result, 'User registered successfully', 201);
        } catch (\Throwable $e){
            // Log the error for debugging
            \Log::error('User registration failed: '.$e->getMessage(), [
                'stack' => $e->getTraceAsString()
            ]);
            return ApiResponse::error('Registration failed. Please try again later.', 500);
        }
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return ApiResponse::error('Invalid credentials', 401);
        }

        $result = $this->authService->login($user);
        return ApiResponse::success($result, 'Login successful');
    }

    public function logout()
    {
        $this->authService->logout(auth()->user());
        return ApiResponse::success(null, 'Logged out successfully');
    }

    public function me()
    {
        return ApiResponse::success(auth()->user(), 'Current user details');
    }
}