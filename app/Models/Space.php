<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
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
}
