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
        'title',
        'address',
        'description',
        'is_closed',
        'phone_no',
        'email',
    ];

    protected $primaryKey = 'id';

    public function owner()
    {
        return $this->belongsTo(BusinessOwner::class);
    }
}
