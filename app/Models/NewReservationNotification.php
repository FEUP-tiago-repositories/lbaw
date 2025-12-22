<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewReservationNotification extends Model
{
    use HasFactory;

    protected $table = 'new_reservation_notifications';

    public $timestamps = false; 

    protected $fillable = ['notification_id', 'booking_id'];
    
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notification_id');
    }
}