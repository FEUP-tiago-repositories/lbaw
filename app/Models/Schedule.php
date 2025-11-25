<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    protected $table = 'schedule';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'space_id',
        'start_time',
        'duration',
        'max_capacity',
    ];

    protected $casts = [
        'start_time' => 'datetime',
    ];

    // Relacionamentos
    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class, 'space_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'schedule_id');
    }

    // Métodos de capacidade
    public function hasAvailableCapacity(int $required = 1): bool
    {
        return $this->max_capacity >= $required;
    }

    public function reserveCapacity(int $amount): bool
    {
        if ($this->max_capacity < $amount) {
            return false;
        }

        $this->max_capacity -= $amount;
        return $this->save();
    }

    public function restoreCapacity(int $amount): bool
    {
        $this->max_capacity += $amount;
        return $this->save();
    }

    // Métodos auxiliares
    public function isFuture(): bool
    {
        return $this->start_time > now();
    }
}
