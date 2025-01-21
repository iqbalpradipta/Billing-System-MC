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
        'price'
    ];

    public function transaction() {
        return $this->hasMany(transaction::class);
    }
}
