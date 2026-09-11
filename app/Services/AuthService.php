<?php

namespace App\Services;

use Firebase\JWT\JWT;
use App\Models\User;

class AuthService
{
    /**
     * JWT without expiry vulnerability
     * Bug: Token never expires, stolen tokens valid forever
     */
    public function generateToken(User $user)
    {
        $payload = [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            // VULNERABLE: No 'exp' claim - token never expires!
        ];
        
        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }
    
    /**
     * Weak JWT secret
     */
    public function generateWeakToken(User $user)
    {
        $payload = [
            'user_id' => $user->id,
            'exp' => time() + 3600,
        ];
        
        // VULNERABLE: Hardcoded weak secret
        return JWT::encode($payload, 'secret123', 'HS256');
    }
    
    /**
     * JWT algorithm confusion vulnerability
     */
    public function verifyToken($token)
    {
        // VULNERABLE: Allows 'none' algorithm
        return JWT::decode($token, env('JWT_SECRET'), ['HS256', 'none']);
    }
}
