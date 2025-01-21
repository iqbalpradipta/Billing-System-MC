<?php

namespace App\Helpers;

use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthHelpers
{
    public static function authenticate(Request $request)
    {
        $token = $request->header('Authorization');
        if (!$token) {
            throw new \Exception('Token tidak ditemukan', 401);
        }
        $token = str_replace('Bearer ', '', $token);

        try {
            return JWTAuth::parseToken()->authenticate($token);
        } catch (JWTException $e) {
            throw new \Exception('Token tidak valid', 401);
        }
    }
}
