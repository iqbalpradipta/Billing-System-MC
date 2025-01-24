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
        $transaction = Transaction::with(['wallet', 'vps'])
            ->latest()
            ->paginate(5);

        return new TransactionResource(true, 'Success Get Transaction with Relations', $transaction);
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
            $wallet_id = $userArray['wallet']['id'];

            $transaction = Transaction::create([
                'amount' => 0,
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

    public function UpdateTransaction(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'vps_id' => 'required|exists:vps,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $user = AuthHelpers::authenticate($request);
            $transaction = Transaction::findOrFail($id);

            if ($transaction->wallet_id !== $user->wallet->id) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Unauthorized'
                ], 403);
            }

            $transaction->update([
                'vps_id' => $request->vps_id,
            ]);

            return new TransactionResource(true, 'Transaction Updated Successfully', $transaction);
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Token tidak valid'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function DeleteTransaction(Request $request, $id) {
        try {
            $user = AuthHelpers::authenticate($request);
            $transaction = Transaction::findOrFail($id);

            if ($transaction->wallet_id !== $user->wallet->id) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Unauthorized'
                ], 403);
            }

            $transaction->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaction Deleted Successfully'
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Token tidak valid'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function UpdateTransactionEveryHours(Request $request)
    {
        try {
            $user = AuthHelpers::authenticate($request);
            $wallet = $user->wallet;

            if (!$wallet) {
                return response()->json(['message' => 'Wallet not found'], 404);
            }

            $transactions = Transaction::where('wallet_id', $wallet->id)->get();

            if ($transactions->isEmpty()) {
                return response()->json(['message' => 'No transactions to update'], 404);
            }

            $notification = null;

            foreach ($transactions as $transaction) {
                $vps = $transaction->vps;

                if ($vps) {
                    if ($wallet->balance >= $vps->price) {
                        $transaction->amount += $vps->price;
                        $transaction->type = 'Running';
                        $transaction->save();

                        $wallet->balance -= $vps->price;
                        $wallet->save();

                        if ($wallet->initial_balance > 0) {
                            $walletThreshold = $wallet->balance / $wallet->initial_balance;
                            if ($walletThreshold < 0.1) {
                                $notification = 'Saldo akan segera habis';
                            }
                        }

                    } else {
                        $transaction->type = 'Suspend';
                        $transaction->save();
                    }
                }
            }

            $response = [
                'status' => 'success',
                'message' => 'Transactions updated successfully',
            ];

            if ($notification) {
                $response['notification'] = $notification;
            }

            return response()->json($response);
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Token tidak valid'
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
