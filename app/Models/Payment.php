<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';
    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'value',
        'is_discounted',
        'is_accepted',
        'payment_provider_ref',
        'time_stamp',
    ];

    protected $casts = [
        'value' => 'float',
        'is_discounted' => 'boolean',
        'is_accepted' => 'boolean',
        'time_stamp' => 'datetime',
    ];

    const PAYMENT_PROVIDERS = [
        'Credit/Debit Card',
        'MB Way',
        'Paypal',
    ];

    // Relacionamento
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    // Método principal: cálculo de valor
    public static function calculateValue(int $durationInMinutes, int $numberOfPersons, int $scheduleDuration = 30, ?float $discountPercentage = null): float
    {
        $numberOfSchedules = (int) ceil($durationInMinutes / $scheduleDuration);
        $totalCapacityUsed = $numberOfSchedules * $numberOfPersons;
        $baseValue = $totalCapacityUsed * 10.0;

        if ($discountPercentage && $discountPercentage > 0) {
            $discountAmount = ($baseValue * $discountPercentage) / 100;
            return round($baseValue - $discountAmount, 2);
        }

        return round($baseValue, 2);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->time_stamp)) {
                $payment->time_stamp = now();
            }
        });
    }
}
