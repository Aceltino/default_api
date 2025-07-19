<?php

namespace App\Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class Auth
{
    /**
     * Verifica se o usuário está autenticado
     * 
     * @return bool
     */
    private static $secretKey = 'mykey';
    private static $algorithm = 'HS256';

    public static function check(): bool
    {
        $token = self::getBearerToken();
        
        if (!$token) {
            return false;
        }

        try {
            $decoded = JWT::decode($token, new Key(self::$secretKey, self::$algorithm));
            return !empty($decoded->id); // Verifica se tem um ID de usuário
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function user()
    {
        $token = self::getBearerToken();
        
        if (!$token) {
            return null;
        }

        try {
            return JWT::decode($token, new Key(self::$secretKey, self::$algorithm));
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function generateToken(array $userData): string
    {
        $payload = [
            'id' => $userData['id'],
            'email' => $userData['email'],
            'iat' => time(),
            'exp' => time() + (60 * 60) // Expira em 1 hora
        ];

        return JWT::encode($payload, self::$secretKey, self::$algorithm);
    }

    private static function getBearerToken(): ?string
    {
        $headers = getallheaders();
        
        // Obter do header Authorization
        if (isset($headers['Authorization'])) {
            if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
                return $matches[1];
            }
        }
        
        // Obter do cookie (se preferir)
        return $_COOKIE['jwt_token'] ?? null;
    }

    /**
     * Retorna o usuário autenticado
     * 
     * @return mixed
     */


    /**
     * Retorna o ID do usuário autenticado
     * 
     * @return int|null
     */
    public static function id(): ?int
    {
        return $_SESSION['user']['id'] ?? null;
    }
}