<?php

namespace App\Http\Controllers;

use App\Http\Resources\WalletResource;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController
{
    public function index() {
        $wallet = Wallet::latest()->pagination(5);

        return new WalletResource(true, 'Success Get Data', $wallet);
    }
}
