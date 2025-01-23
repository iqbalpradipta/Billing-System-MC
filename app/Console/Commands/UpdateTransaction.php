<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UpdateTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-transaction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update amount based on wallet balance';

    /**
     * Execute the console command.
     */
    public function handle() {
        Log::info('UpdateTransaction is running');
        DB::transaction(function () {
            $transactions = Transaction::where('updated_at', '<=', now()->subHour())->get();

            foreach ($transactions as $transaction) {
                $vps = $transaction->vps;
                $wallet = $transaction->wallet;

                if ($vps && $wallet) {
                    if ($wallet->balance >= $vps->price) {
                        $transaction->amount += $vps->price;
                        $transaction->type = 'Running';
                        $transaction->save();

                        $wallet->balance -= $vps->price;
                        $wallet->save();

                        Log::info('Transaksi diperbarui', ['id' => $transaction->id]);
                    }
                }
            }
        });
    }
}
