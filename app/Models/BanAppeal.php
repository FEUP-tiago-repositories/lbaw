<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BanAppeal extends Model
{
    protected $table = 'ban_appeal';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'ban_id',
        'appeal',
        'time_stamp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ban()
    {
        return $this->belongsTo(Ban::class);
    }
}
