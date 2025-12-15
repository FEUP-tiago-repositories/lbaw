<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notification';
    public $timestamps = false;
    protected $fillable = ['user_id', 'content', 'is_read', 'time_stamp'];
    
    protected $casts = [
        'is_read' => 'boolean',
        'time_stamp' => 'datetime',
    ];

    public function bookingConfirmation()
    {
        return $this->hasOne(BookingConfirmationNotification::class, 'notification_id');
    }

    public function bookingCancellation()
    {
        return $this->hasOne(BookingCancellationNotification::class, 'notification_id');
    }

    public function bookingReminder()
    {
        return $this->hasOne(BookingReminderNotification::class, 'notification_id');
    }
    
    public function getNotificationTypeAttribute()
    {
        if ($this->bookingConfirmation) return 'Confirmation';
        if ($this->bookingCancellation) return 'Cancelation';
        if ($this->bookingReminder) return 'Reminder';
        return 'Information';
    }

    public function getStyleAttribute()
    {
        $data = [
            'color' => 'emerald',
            'title' => 'Notification',
            'icon'  => 'M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5'
        ];

        if ($this->bookingConfirmation) {
            return [
                'color' => 'emerald',
                'title' => 'Reservation Confirmed',
                'icon'  => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5'
            ];
        } 
        
        if ($this->bookingCancellation) {
            return [
                'color' => 'red',
                'title' => 'Reservation Cancelled',
                'icon'  => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'
            ];
        }

        if ($this->bookingReminder) {
            return [
                'color' => 'emerald',
                'title' => 'Reminder',
                'icon'  => 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'
            ];
        }

        if ($this->reviewNotification) {
            return [
                'color' => 'indigo',
                'title' => 'New Review',
                'icon'  => 'M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z'
            ];
        }

        return $data;
    }
}