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
        'service_rating',
    ];

    protected $primaryKey = 'id';

    // special casts
    protected $casts = [
        'time_stamp' => 'datetime',
        'environment_rating' => 'integer',
        'equipment_rating' => 'integer',
        'service_rating' => 'integer',
    ];

    /**
     * Get the booking this belongs to
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    /**
     * Get the customer who wrote this review
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Get all responses to this review
     */
    // public function responses()
    // {
    //     return $this->hasMany(ReviewResponse::class, 'review_id');
    // }

    /**
     * Get the space through the booking
     */
    public function space()
    {
        return $this->hasOneThrough(
            Space::class,
            Booking::class,
            'id',           // Foreign key on bookings table
            'id',           // Foreign key on spaces table
            'booking_id',   // Local key on reviews table
            'space_id'      // Local key on bookings table
        );
    }

    // get response
    public function response()
    {
        return $this->hasOne(Response::class, 'review_id');
    }
}
