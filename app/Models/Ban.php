<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    protected $table = 'ban';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'admin_id',
        'motive',
        'time_stamp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
