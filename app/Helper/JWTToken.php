<?php
namespace App\Helper;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;


class JWTToken{
    public static function createToken($userEmail) :string{
        $key =env('JWT_KEY');


    $payload = [
        'iss' => 'laravel-token',
        'iat' => time(),
       ' exp' => time() + 36*36,
       'email' => $userEmail
    ];
     return JWT::encode($payload, $key, 'HS256');
    }
    public static function createTokenSetPassword($userEmail) :string{
        $key = env('JWT_KEY');
    $payload = [
        'iss' => 'laravel-token',
        'iat' => time(),
       ' exp' => time() + 36*20,
       'userEmail' => $userEmail
    ];
     return JWT::encode($payload, $key, 'HS256');
    }


    public static function verifyToken($token): string{
        try{
            $key = env('JWT_KEY');
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            return $decoded->email;
        } catch(Exception $e){
            return $e->getMessage();
            // return 'unauthorized';
        }
       
    }
   
}
