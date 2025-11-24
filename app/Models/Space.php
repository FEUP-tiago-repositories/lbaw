<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    protected $table = 'space';
    public $timestamps = false;

    protected $fillable = ['title', 'address', 'description', 'is_closed'];

    protected $casts = [
        'is_closed' => 'boolean',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'space_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'space_id');
    }

    // Stub para media (retorna coleção vazia)
    public function media()
    {
        return $this->hasMany(Media::class, 'space_id');
    }
}
