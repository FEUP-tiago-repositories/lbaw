<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    public $timestamps = false;

    //     CREATE TABLE response (
    //     id INT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    //     owner_id INT REFERENCES business_owner (id) ON DELETE SET NULL,
    //     review_id INT NOT NULL REFERENCES review (id) ON DELETE CASCADE,
    //     text VARCHAR(300) NOT NULL,
    //     time_stamp TIMESTAMP NOT NULL DEFAULT NOW()
    // );

    protected $table = 'response';

    protected $fillable = [
        'owner_id',
        'review_id',
        'text',
        'time_stamp',
    ];

    protected $primaryKey = 'id';

    // casts
    protected $casts = [
        'time_stamp' => 'datetime',
    ];

    public function review()
    {
        return $this->belongsTo(Review::class, 'review_id');
    }

    //associated customer
    public function owner(){
        return $this->belongsTo(BusinessOwner::class,'owner_id');
    }
}
