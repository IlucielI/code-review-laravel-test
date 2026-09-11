<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService
{
    private $secretKey = 'your-secret-key';

    // Vulnerable: JWT without expiration
    public function generateToken($userId)
    {
        $payload = [
            'user_id' => $userId,
            'role' => 'admin',
            'iat' => time(),
            // Missing 'exp' claim - token never expires
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    // Vulnerable: No expiration validation
    public function validateToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            // No check for expiration even if present
            return $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }

    // Vulnerable: Long-lived refresh token without rotation
    public function generateRefreshToken($userId)
    {
        $payload = [
            'user_id' => $userId,
            'type' => 'refresh',
            // No expiration - valid forever
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    // Vulnerable: No token revocation mechanism
    public function logout($token)
    {
        // Token remains valid even after logout
        // No blacklist or token invalidation
        return true;
    }
}
