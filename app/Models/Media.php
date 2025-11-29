<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'space_id',
        'media_url',
        'is_cover',
    ];

    /**
     * Get the space that owns this media
     */
    public function space()
    {
        return $this->belongsTo(Space::class, 'space_id');
    }
}
