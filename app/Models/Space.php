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
        'num_favorites',
        'num_reviews',
        'current_environment_rating',
        'current_equipment_rating',
        'current_service_rating',
        'current_total_rating',
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

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'space_id');
    }
}
