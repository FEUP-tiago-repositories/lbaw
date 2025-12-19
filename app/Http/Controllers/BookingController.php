<?php

namespace App\Http\Controllers;

use App\Events\NotificationSent;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Payment;
use App\Models\Space;
use App\Models\Schedule;
use App\Models\BusinessOwner;
use App\Models\Notification;
use App\Models\BookingConfirmationNotification;
use App\Models\BookingCancellationNotification;
use App\Models\NewReservationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display user's bookings
     * GET /user/{user_id}/my_reservations
     */
    public function index($user_id)
    {
        if (!Auth::check() || Auth::id() != $user_id) {
            return redirect()->route('login');
        }

        $customer = Customer::where('user_id', $user_id)->firstOrFail();

        $bookings = Booking::where('customer_id', $customer->id)
            ->with(['space', 'schedule', 'payment'])
            ->orderBy('booking_created_at', 'desc')
            ->get();

        $futureReservations = $bookings->filter(fn($b) => $b->isFuture() && !$b->is_cancelled);
        $pastReservations = $bookings->filter(fn($b) => $b->isPast() && !$b->is_cancelled);
        $cancelledReservations = $bookings->filter(fn($b) => $b->is_cancelled);

        return view('bookings.index', compact('futureReservations', 'pastReservations', 'cancelledReservations'));
    }

    /**
     * Show booking edit form
     * GET /bookings/{booking}/edit
     */
    public function edit($booking_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $booking = Booking::with(['space', 'schedule', 'payment'])->findOrFail($booking_id);

        return view('bookings.edit', compact('booking'));
    }

    public function store(Request $request, $space_id, $schedule_id)
    {
        $validated = $request->validate([
            'customer_id' => 'required|integer|exists:customer,id',
            'duration' => 'required|integer|min:15',
            'number_of_persons' => 'required|integer|min:1',
            'payment_provider_ref' => 'required|string|in:' . implode(',', Payment::PAYMENT_PROVIDERS)
        ]);

        DB::beginTransaction();

        try {
            $schedule = Schedule::findOrFail($schedule_id);
            $result = ScheduleController::getAffectedSchedules(
                $schedule,
                $validated['duration'],
                $validated['number_of_persons']
            );

            if (!$result['success']) {
                return response()->json(['error' => $result['message']], 400);
            }

            $totalPrice = $this->calculatePrice(
                $space_id,
                $validated['duration'],
                $validated['number_of_persons'],
                $schedule->space->duration
            );

            $payment = Payment::create([
                'value' => $totalPrice,
                'payment_provider_ref' => $validated['payment_provider_ref'],
                'is_accepted' => false,
            ]);

            $booking = Booking::create([
                'customer_id' => $validated['customer_id'],
                'schedule_id' => $schedule_id,
                'space_id' => $space_id,
                'total_duration' => $validated['duration'],
                'number_of_persons' => $validated['number_of_persons'],
                'payment_id' => $payment->id,
                'booking_created_at' => now(),
                'is_cancelled' => false,
            ]);

            // Reservar capacidade
            ScheduleController::reserveCapacity($result['schedules'], $validated['number_of_persons']);

            $space = Space::findOrFail($space_id);
            $ownerUserId = null;

            if (!empty($space->owner_id)) {
                $businessOwner = BusinessOwner::find($space->owner_id);
                if ($businessOwner) {
                    $ownerUserId = $businessOwner->user_id;
                }
            }

            if ($ownerUserId && $ownerUserId != Auth::id()) {

                $ownerNotif = Notification::create([
                    'user_id' => $ownerUserId,
                    'content' => "You received a new reservation",
                    'is_read' => false,
                    'time_stamp' => now(),
                ]);

                NewReservationNotification::create([
                    'notification_id' => $ownerNotif->id,
                    'booking_id' => $booking->id
                ]);

                event(new NotificationSent($ownerNotif));
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'booking_id' => $booking->id,
                'payment' => ['id' => $payment->id, 'value' => $payment->value]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create booking: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $space_id, $schedule_id, $booking_id)
    {
        $booking = Booking::findOrFail($booking_id);

        $validated = $request->validate([
            'new_schedule_id' => 'required|integer|exists:schedule,id',
            'duration' => 'required|integer|min:15',
            'number_of_persons' => 'required|integer|min:1',
            'payment_provider_ref' => 'required|string'
        ]);

        DB::beginTransaction();

        try {
            $oldResult = ScheduleController::getAffectedSchedules(
                $booking->schedule,
                $booking->total_duration,
                $booking->number_of_persons
            );

            if ($oldResult['success']) {
                ScheduleController::restoreCapacity($oldResult['schedules'], $booking->number_of_persons);
            }

            $newSchedule = Schedule::findOrFail($validated['new_schedule_id']);
            $newResult = ScheduleController::getAffectedSchedules(
                $newSchedule,
                $validated['duration'],
                $validated['number_of_persons']
            );

            if (!$newResult['success']) {
                DB::rollBack();
                return response()->json(['error' => $newResult['message']], 400);
            }

            $newPrice = $this->calculatePrice(
                $space_id,
                $validated['duration'],
                $validated['number_of_persons'],
                $newSchedule->space->duration
            );

            $oldPrice = $booking->payment->value;
            $requiresPayment = $newPrice > $oldPrice;
            $additionalPayment = $requiresPayment ? ($newPrice - $oldPrice) : 0;

            if ($requiresPayment) {
                $payment = Payment::create([
                    'value' => $additionalPayment,
                    'payment_provider_ref' => $validated['payment_provider_ref'],
                    'is_accepted' => false,
                ]);
                $booking->payment_id = $payment->id;
            } else {
                if($booking->payment) {
                    $booking->payment->update(['value' => $newPrice]);
                }
            }

            $booking->update([
                'schedule_id' => $validated['new_schedule_id'],
                'total_duration' => $validated['duration'],
                'number_of_persons' => $validated['number_of_persons'],
            ]);

            // Reservar nova capacidade
            ScheduleController::reserveCapacity($newResult['schedules'], $validated['number_of_persons']);

            DB::commit();

            return response()->json([
                'success' => true,
                'booking_id' => $booking->id,
                'requires_payment' => $requiresPayment,
                'additional_payment' => $additionalPayment
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update booking: ' . $e->getMessage()], 500);
        }
    }

    public function cancel($space_id, $schedule_id, $booking_id)
    {
        try {
            $booking = Booking::with(['schedule'])->findOrFail($booking_id);

            if ($booking->is_cancelled) {
                return response()->json(['error' => 'Booking already cancelled'], 400);
            }

            if (!$booking->isFuture()) {
                return response()->json(['error' => 'Cannot cancel past bookings'], 400);
            }

            DB::beginTransaction();

            $result = ScheduleController::getAffectedSchedules(
                $booking->schedule,
                $booking->total_duration,
                $booking->number_of_persons
            );

            if ($result['success']) {
                ScheduleController::restoreCapacity($result['schedules'], $booking->number_of_persons);
            }

            $booking->is_cancelled = true;
            $booking->save();

            $notification = Notification::create([
                'user_id' => $booking->customer->user_id,
                'content' => 'Your reservation has been cancelled.',
                'is_read' => false,
                'time_stamp' => now(),
            ]);

            BookingCancellationNotification::create(['notification_id' => $notification->id, 'booking_id' => $booking->id]);

            event(new NotificationSent($notification));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Failed to cancel booking: ' . $e->getMessage()
            ], 500);
        }
    }

    private function calculatePrice(int $spaceId, int $duration, int $persons, int $scheduleDuration): float
    {
        $discount = Discount::where('space_id', $spaceId)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        return Payment::calculateValue($duration, $persons, $scheduleDuration, $discount?->percentage);
    }

    public function selectSpace()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->businessOwner) {
            abort(403, 'Only business owners can access this page.');
        }

        $spaces = Space::where('owner_id', $user->businessOwner->id)
            ->with(['sportType', 'coverImage'])
            ->orderBy('title', 'asc')
            ->get();

        return view('spaces.select', compact('spaces'));
    }

    /**
     * Show bookings for a specific space with both List and Calendar views
     * GET /spaces/{space}/bookings
     */
    public function spaceBookings(Request $request, $space_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->businessOwner) {
            abort(403, 'Only business owners can access this page.');
        }

        $space = Space::findOrFail($space_id);

        if ($space->owner_id !== $user->businessOwner->id) {
            abort(403, 'You do not own this space.');
        }

        // Get selected date with validation
        $selectedDateParam = $request->input('date', Carbon::today()->format('Y-m-d'));
        
        // Validate date format (must be YYYY-MM-DD)
        try {
            // If date is invalid or incomplete, default to today
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDateParam)) {
                $selectedDate = Carbon::today()->format('Y-m-d');
            } else {
                $date = Carbon::parse($selectedDateParam);
                $selectedDate = $date->format('Y-m-d');
            }
        } catch (\Exception $e) {
            // If parsing fails, use today
            $selectedDate = Carbon::today()->format('Y-m-d');
        }
        
        $date = Carbon::parse($selectedDate);

        // Calculate week range for week filter
        $weekStart = $date->copy()->startOfWeek();
        $weekEnd = $date->copy()->endOfWeek();

        // Get all bookings for list view
        $allBookings = Booking::where('space_id', $space_id)
            ->with(['customer.user', 'schedule', 'payment'])
            ->orderBy('booking_created_at', 'desc')
            ->get();

        $futureReservations = $allBookings->filter(fn($b) => $b->isFuture() && !$b->is_cancelled);
        $pastReservations = $allBookings->filter(fn($b) => $b->isPast() && !$b->is_cancelled);
        $cancelledReservations = $allBookings->filter(fn($b) => $b->is_cancelled);

        // Get all schedules for the selected day
        $daySchedules = Schedule::where('space_id', $space_id)
            ->whereDate('start_time', $date->format('Y-m-d'))
            ->orderBy('start_time')
            ->get();

        // Build complete timeline based on space duration
        $timeline = $this->buildDayTimeline($date, $space, $daySchedules);

        $hasAnyBookings = $allBookings->isNotEmpty();

        return view('bookings.space-bookings', compact(
            'space',
            'selectedDate',
            'futureReservations',
            'pastReservations',
            'cancelledReservations',
            'timeline',
            'hasAnyBookings',
            'weekStart',
            'weekEnd'
        ));
    }

    /**
     * Build complete timeline with slots based on space duration
     * Shows gray slots (0/0 capacity) for time slots without schedules
     */
    private function buildDayTimeline($date, $space, $schedules)
    {
        $timeline = [];

        // Parse opening and closing times (defaults if not set)
        $openingTime = $space->opening_time ?? '08:00:00';
        $closingTime = $space->closing_time ?? '22:00:00';
        $slotDuration = $space->duration ?? 30; // Duration in minutes from space

        // Parse times to get hour and minute
        list($openHour, $openMinute) = explode(':', $openingTime);
        list($closeHour, $closeMinute) = explode(':', $closingTime);

        $openHour = (int)$openHour;
        $openMinute = (int)$openMinute;
        $closeHour = (int)$closeHour;
        $closeMinute = (int)$closeMinute;

        // Generate all slots from opening to closing time based on space duration
        $currentTime = Carbon::parse($date)->setTime($openHour, $openMinute, 0);
        $endTime = Carbon::parse($date)->setTime($closeHour, $closeMinute, 0);

        while ($currentTime < $endTime) {
            $slotKey = $currentTime->format('H:i');

            // Find schedule for this slot
            $schedule = $schedules->first(function($s) use ($currentTime) {
                return Carbon::parse($s->start_time)->format('H:i') === $currentTime->format('H:i');
            });

            if ($schedule) {
                // Slot with schedule - show actual bookings and capacity
                $bookings = Booking::where('schedule_id', $schedule->id)
                    ->with(['customer.user'])
                    ->get();

                $activeBookings = $bookings->where('is_cancelled', false);
                $usedCapacity = $activeBookings->sum('number_of_persons');

                // max_capacity is remaining, so total = used + remaining
                $totalCapacity = $usedCapacity + $schedule->max_capacity;
                $occupancyPercentage = $totalCapacity > 0 ? ($usedCapacity / $totalCapacity) * 100 : 0;

                $timeline[] = [
                    'time' => $slotKey,
                    'schedule' => $schedule,
                    'bookings' => $bookings,
                    'used_capacity' => $usedCapacity,
                    'available_capacity' => $schedule->max_capacity,
                    'total_capacity' => $totalCapacity,
                    'occupancy_percentage' => $occupancyPercentage,
                    'has_bookings' => $bookings->isNotEmpty(),
                    'has_schedule' => true
                ];
            } else {
                // Slot without schedule - show gray slot with 0/0 capacity
                $timeline[] = [
                    'time' => $slotKey,
                    'schedule' => null,
                    'bookings' => collect([]),
                    'used_capacity' => 0,
                    'available_capacity' => 0,
                    'total_capacity' => 0,
                    'occupancy_percentage' => 0,
                    'has_bookings' => false,
                    'has_schedule' => false
                ];
            }

            // Move to next slot based on space duration
            $currentTime->addMinutes($slotDuration);
        }

        return collect($timeline);
    }
}
