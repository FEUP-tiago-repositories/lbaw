<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';
    public $timestamps = false;

    protected $fillable = [
        'space_id',
        'customer_id',
        'schedule_id',
        'booking_created_at',
        'is_cancelled',
        'number_of_persons',
        'total_duration',
    ];

    protected $casts = [
        'booking_created_at' => 'datetime',
        'is_cancelled' => 'boolean',
    ];

    // Relacionamentos
    public function space()
    {
        return $this->belongsTo(Space::class, 'space_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'booking_id');
    }

    // Métodos essenciais
    public function isFuture(): bool
    {
        return $this->schedule && $this->schedule->start_time >= now();
    }

    public function isPast(): bool
    {
        return $this->schedule && $this->schedule->start_time < now();
    }

    protected static function boot()
    {
        parent::boot();

    }
}
