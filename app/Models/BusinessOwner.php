<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessOwner extends Model
{
    public $timestamps = false;

    protected $table = 'business_owner';

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function spaces(){
        return $this->hasMany(Space::class);
    }
}
