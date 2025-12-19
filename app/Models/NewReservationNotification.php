<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewReservationNotification extends Model
{
    use HasFactory;

    // Define o nome da tabela (opcional, mas boa prática para garantir)
    protected $table = 'new_reservation_notifications';

    // AVISA O LARAVEL QUE ESTA TABELA NÃO TEM TIMESTAMPS
    public $timestamps = false; 

    protected $fillable = ['notification_id', 'booking_id'];
    
    // Relação com a reserva
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    // Relação com a notificação pai (útil se precisares de subir)
    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notification_id');
    }
}