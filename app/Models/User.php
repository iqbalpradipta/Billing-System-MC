<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
        ];
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'status'
    ];

    public function wallet() {
        return $this->hasOne(Wallet::class);
    }

    public function vps() {
        return $this->hasMany(vps::class);
    }
}
