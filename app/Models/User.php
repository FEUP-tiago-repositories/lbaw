<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user';
    public $timestamps = false;

    protected $fillable = ['username', 'email', 'password'];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'user_id');
    }
}
