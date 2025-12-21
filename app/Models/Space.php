<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    // space is associated with one business owner
    public $timestamps = false;

    // our table is named Space, so we execute this command:
    protected $table = 'space';

    protected $fillable = [
        'owner_id',
        'sport_type_id',
        'title',
        'address',
        'description',
        'is_closed',
        'phone_no',
        'email',
        'opening_time',
        'closing_time',
        'duration',
        'num_favorites',
        'num_reviews',
        'current_environment_rating',
        'current_equipment_rating',
        'current_service_rating',
        'current_total_rating',
    ];

    protected $casts = [
        'opening_time' => 'string',
        'closing_time' => 'string',
        'duration' => 'integer',
    ];

    protected $primaryKey = 'id';

    public function owner()
    {
        return $this->belongsTo(BusinessOwner::class);
    }

    public function sportType()
    {
        return $this->belongsTo(SportType::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    /**
     * Get the cover image of a space
     */
    public function coverImage()
    {
        return $this->hasOne(Media::class, 'space_id')->where('is_cover', true);
    }

    // **
    // Get all booking associated with a space
    //  */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'space_id');
    }

    /**
     * Get all reviews associated with a space
     */
    public function reviews()
    {
        return $this->hasManyThrough(
            Review::class, // the final model
            Booking::class, // the model it needs to come trough
            'space_id',
            'booking_id',
            'id',
            'id'
        );
    }

    /**
     * Get all schedules for this space
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'space_id');
    }

    public function favoritedByCustomers()
    {
        return $this->belongsToMany(Customer::class, 'favorited', 'space_id', 'customer_id');
    }
    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }
}
