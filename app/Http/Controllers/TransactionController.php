<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Helpers\AuthHelpers;
use Illuminate\Support\Facades\Log;

class TransactionController
{
    public function GetDataTransaction() {
        $transaction = Transaction::latest()->paginate(5);

        return new TransactionResource(true, 'Success Get Transaction', $transaction);
    }

    public function CreateTransaction(Request $request) {
        $validator = Validator::make($request->all(), [
            'vps_id' => 'required|exists:vps,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $user = AuthHelpers::authenticate($request);
            $userArray = $user->toArray();
            Log::info($userArray);
            $wallet_id = $userArray['wallet']['id'];
            $balance = $userArray['wallet']['balance'];

            $transaction = Transaction::create([
                'amount' => $balance,
                'type' => 'Published',
                'wallet_id' => $wallet_id,
                'vps_id' => $request->vps_id,
            ]);

            return new TransactionResource(true, 'Transaction Created Successfully', $transaction);
        } catch (JWTException $e) {
            return response()->json([
                'error' =>
                'Token tidak valid'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
