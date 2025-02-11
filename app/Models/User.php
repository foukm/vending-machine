<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'username','password', 'deposit', 'role'
    ];

    protected $hidden = ['password'];


    public function saveUser($data)
    {
        $this->username = strip_tags($data['username']);
        $this->password = Hash::make($data['password']);
        $this->role = $data['role'];
        return $this->save();

    }

    public function updateUser($data)
    {
        $this->username = strip_tags($data['username']);
        $this->password = Hash::make($data['password']);
        return $this->save();
    }

    public function deposit($amount)
    {
        $this->deposit += strip_tags($amount);
        return $this->save();
    }

    public function resetDeposit()
    {
        $this->deposit = 0;
        return $this->save();
    }
    public function buy($cost)
    {
        $this->deposit -= $cost;
        return $this->save();
    }
}
