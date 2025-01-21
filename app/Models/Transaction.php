<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'type',
        'wallet_id',
        'vps_id'
    ];

    public function vps() {
        return $this->belongsTo(vps::class);
    }

    public function wallet() {
        return $this->belongsTo(Wallet::class);
    }
}
