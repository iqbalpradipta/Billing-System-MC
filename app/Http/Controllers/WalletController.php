<?php

namespace App\Http\Controllers;

use App\Http\Resources\WalletResource;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class WalletController
{
    public function GetDataWallet() {
        $wallet = Wallet::latest()->paginate(5);

        return response()->json([
            'success' => true,
            'message' => 'Success Get Wallet',
            'data' => $wallet
        ], 200);
    }

    public function CreateWallet(Request $request, User $user) {
        try {
            $wallet = Wallet::create([
                'balance' => '0',
                'user_id' => $user->id
            ]);

            return new WalletResource(true, 'Success create Wallet', $wallet);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function UpdateWallet(Request $request) {
        $validator = Validator::make($request->all(), [
            'balance' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        try {
            $token = $request->header('Authorization');
            if (!$token) {
                return response()->json(['error' => 'Token tidak ditemukan'], 401);
            }
            $token = str_replace('Bearer ', '', $token);

            $user = JWTAuth::parseToken()->authenticate($token);

            if($user) {
                $wallet = Wallet::where('user_id', $user->id)->first();
                if (!$wallet) {
                    return response()->json([
                        'success' => false,
                        'message' =>
                        'Wallet tidak ditemukan'
                    ], 404);
                }

                $wallet->update([
                    'balance' => $request->balance,
                ]);

                return new WalletResource(true, 'Success update wallet', $wallet);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'your token is not valid'
                ]);
            };
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
