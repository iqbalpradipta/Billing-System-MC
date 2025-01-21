<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController
{
    public function GetDataUsers() {
        $user = User::with('wallet')->leftJoin('wallets', 'users.id', '=', 'wallets.user_id')->get();

        return new UserResource(true, 'Success Get Users', $user);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $walletController = new WalletController();
            $walletController->createWallet($request, $user);

            return new UserResource(true, 'Register Success', $user);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'failed',
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $credentials = $request->only('email', 'password');

            if(!$token = auth()->guard('api')->attempt($credentials)) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Email or Password is wrong!'
                ], 401);
            }

            JWTAuth::factory()->setTTL(1440);
            $user = auth()->guard('api')->user();
            $walletId = $user->wallet ? $user->wallet->id : null;
            $balance = $user->wallet ? $user->wallet->balance : null;
            $customClaims = ['wallet_id' => $walletId, 'balance' => $balance];
            $token = JWTAuth::claims($customClaims)->attempt($credentials);

            return response()->json([
                'status' => 'success',
                'messages' => 'Login Success',
                'token' => $token
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'failed',
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request) {
        $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

        if($removeToken) {
            return response()->json([
                'status' => 'success',
                'message' => 'Logout Berhasil',
            ]);
        }
    }
}
