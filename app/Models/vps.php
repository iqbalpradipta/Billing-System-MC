<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vps extends Model
{
    use HasFactory;

    protected $fillable = [
        'cpu',
        'ram',
        'storage',
        'user_id',
        'wallet_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function wallet() {
        return $this->belongsTo(Wallet::class);
    }
}
