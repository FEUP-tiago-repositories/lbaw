<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportType extends Model
{
    public $timestamps = false;

    /**
     * The table associated with Sport Type
     *
     * @var string
     */
    protected $table = 'sport_type';

    /**
     * The attributes that are mass assignable
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'name',
    ];

    public function spaces()
    {
        return $this->hasMany(Space::class, 'sport_type_id');
    }
}
