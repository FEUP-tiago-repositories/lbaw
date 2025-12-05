<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    // disable timestamps default
   public $timestamps = false;

    // our table is named Space, so we execute this command:
    protected $table = 'review';

    protected $fillable = [
        'customer_id',
        'booking_id',
        'time_stamp',
        'text',
        'environment_rating',
        'equipment_rating',
        'service_rating'
    ];

    protected $primaryKey = 'id';
}
