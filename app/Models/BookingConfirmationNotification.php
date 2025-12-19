<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingConfirmationNotification extends Model
{
    protected $table = 'booking_confirmation_notification';
    
    public $timestamps = false;

    protected $fillable = ['notification_id', 'booking_id'];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}