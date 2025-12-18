<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public $timestamps = false;

    protected $table = 'customer';

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function bookings(){
        return $this->hasMany(Booking::class, 'customer_id');
    }

    public function reviews(){
        return $this->hasMany(Review::class, 'customer_id');
    }
}
