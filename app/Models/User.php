<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $timestamps = false;

    protected $table = 'user';

    protected $fillable = [
        'user_name',
        'email',
        'phone_no',
        'is_deleted',
        'is_banned',
        'password',
        'birth_date',
        'profile_pic_url',
    ];

    // Relation Ships
    public function businessOwner()
    {
        return $this->hasOne(BusinessOwner::class, 'user_id', 'id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'user_id', 'id');
    }
}
