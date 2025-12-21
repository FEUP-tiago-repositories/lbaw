<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discount extends Model
{
    protected $table = 'discount';
    public $timestamps = false;

    protected $fillable = ['space_id', 'code', 'percentage', 'start_date', 'end_date'];

    public function scopeValid($query) {
        return $query->where('start_date', '<=', now())
                     ->where('end_date', '>=', now());
    }

    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = $value ? strtoupper($value) : null;
    }

    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }
}
