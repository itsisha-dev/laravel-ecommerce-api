<?php

namespace App\Services;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    /**
     * Register a new user and return token
     */
    public function register(array $data): array
    {
        $user = User::create($data); // Password auto-hashed via $casts
        
        $token = $user->createToken('my-project-api-token')->plainTextToken;

        $jwtToken = JWTAuth::fromUser($user);

        return [
            'user' => $user,
            'token' => $token,
            'jwt_token' => $jwtToken,
        ];
    }

    /**
     * Login user and return token
     */
    public function login(User $user): array
    {
        // Sanctum token (for frontend)
        $token = $user->createToken('my-project-api-token')->plainTextToken;

        // JWT token (for FastAPI)
        // $jwtToken = JWTAuth::fromUser($user); 
        // OR
        // $jwtToken = JWTAuth::claims([
        //                 'iss' => 'laravel-api',             // who issued the token
        //                 'aud' => 'fastapi-service',         // audience who use or accept the token
        //             ])->fromUser($user);
        // OR
        $jwtToken = JWTAuth::claims([
                            'role' => $user->role,
                            // 'permissions' => $user->permissions,
                        ])->fromUser($user);

        return [
            'user' => $user,
            'token' => $token,
            'jwt_token' => $jwtToken,
        ];
    }

    /**
     * refresh token
     */
    // public function refresh()
    // {
    //     try {
    //         $newToken = auth()->refresh(); // uses refresh_ttl internally

    //         return [
    //             'access_token' => $newToken,
    //             'expires_in' => auth()->factory()->getTTL() * 60,
    //         ];

    //     } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
    //         return ['error' => 'Token refresh failed'];
    //     }
    // }

    /**
     * Logout user (current token)
     */
    public function logout($user): void
    {
        $user->currentAccessToken()->delete();
    }
}