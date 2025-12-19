<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Authenticable
{
    use Notifiable;

    public $timestamps = false;

    protected $table = 'user';

    protected $fillable = [
        'first_name',
        'surname',
        'user_name',
        'email',
        'phone_no',
        'is_deleted',
        'is_banned',
        'password',
        'birth_date',
        'profile_pic_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
        'is_banned' => 'boolean',
    ];

    // Relation Ships
    public function businessOwner()
    {
        return $this->hasOne(BusinessOwner::class, 'user_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'user_id', 'id');
    }
    public function spaces()
    {
        return $this->hasManyThrough(
            Space::class,         
            BusinessOwner::class, 
            'user_id',           
            'owner_id',           
            'id',                 
            'id'                 
        );
    }

    public function ban()
    {
        return $this->hasOne(Ban::class);
    }

    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class, 'user_id')
                    ->orderBy('time_stamp', 'desc');
    }
    
} 