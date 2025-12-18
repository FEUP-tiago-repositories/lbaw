<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\BookingReminderNotification;
use App\Events\NotificationSent;
use Carbon\Carbon;

class SendBookingReminders extends Command
{
    protected $signature = 'reminders:send';

    protected $description = 'Send reminders for bookings happening within the next 24 hours.';

    public function handle()
    {
        $this->info('Looking for reservations to notify...');

        $targetTimeStart = Carbon::now()->addHours(24);
        $targetTimeEnd = Carbon::now()->addHours(25); 

        $bookings = Booking::whereHas('schedule', function ($query) use ($targetTimeStart, $targetTimeEnd) {
            $query->whereBetween('start_time', [$targetTimeStart, $targetTimeEnd]); 
        })
        ->where('is_cancelled', false)
        ->with('schedule')
        ->with('space') 
        ->get();

        foreach ($bookings as $booking) {
            $alreadySent = BookingReminderNotification::where('booking_id', $booking->id)->exists();

            if (!$alreadySent) {
                
                $spaceName = $booking->space->name ?? 'Desportivo';
                $gameTime = Carbon::parse($booking->schedule->start_time)->format('H:i');

                $notification = Notification::create([
                    'user_id' => $booking->customer->user_id,
                    'content' => "Reminder: your game space {$spaceName} is tomorrow at {$gameTime}!",
                    'is_read' => false,
                    'time_stamp' => now(),
                ]);

                BookingReminderNotification::create([
                    'notification_id' => $notification->id,
                    'booking_id' => $booking->id
                ]);

                event(new NotificationSent($notification));
                
                $this->info("Reminder sent for ID reservation: {$booking->id}");
            }
        }

        $this->info('Processo concluído.');
    }
}