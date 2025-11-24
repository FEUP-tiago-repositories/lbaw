<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\Space;
use App\Models\Customer;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * R405: Display user's reservations page
     */
    public function index($user_id)
    {
        if (!Auth::check() && !app()->environment('local')) {
            return redirect()->route('login');
        }

        $customer = Customer::where('user_id', $user_id)->firstOrFail();

        $bookings = Booking::where('customer_id', $customer->id)
            ->with(['space', 'schedule', 'payment'])
            ->orderBy('booking_created_at', 'desc')
            ->get();

        $futureReservations = $bookings->filter(fn($booking) => $booking->isFuture() && !$booking->is_cancelled);
        $pastReservations = $bookings->filter(fn($booking) => $booking->isPast() && !$booking->is_cancelled);
        $cancelledReservations = $bookings->filter(fn($booking) => $booking->is_cancelled);

        return view('bookings.index', compact('futureReservations', 'pastReservations', 'cancelledReservations'));
    }

    /**
     * R406: Create a new booking
     */
    public function store(Request $request, $space_id, $schedule_id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'customer_id' => 'required|integer|exists:customer,id',
            'duration' => 'required|integer|min:30',
            'number_of_persons' => 'required|integer|min:1',
            'payment_provider_ref' => 'required|string|in:Credit/Debit Card,MB Way,Paypal',
        ]);

        $customer = Customer::findOrFail($validated['customer_id']);
        if ($customer->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $initialSchedule = Schedule::where('id', $schedule_id)
            ->where('space_id', $space_id)
            ->firstOrFail();

        if (!$initialSchedule->isFuture()) {
            return response()->json(['error' => 'Cannot book past schedules'], 400);
        }

        $space = Space::findOrFail($space_id);
        if ($space->is_closed) {
            return response()->json(['error' => 'Space is closed'], 400);
        }

        try {
            DB::beginTransaction();

            // Verificar schedules afetados
            $affectedSchedules = $this->getAffectedSchedules(
                $initialSchedule,
                $validated['duration'],
                $validated['number_of_persons']
            );

            if (!$affectedSchedules['success']) {
                DB::rollBack();
                return response()->json(['error' => $affectedSchedules['message']], 400);
            }

            // Calcular valor
            $discount = Discount::where('space_id', $space_id)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            $finalValue = Payment::calculateValue(
                $validated['duration'],
                $validated['number_of_persons'],
                $initialSchedule->duration,
                $discount?->percentage
            );

            // Criar booking
            $booking = Booking::create([
                'space_id' => $space_id,
                'customer_id' => $validated['customer_id'],
                'schedule_id' => $schedule_id,
                'number_of_persons' => $validated['number_of_persons'],
                'total_duration' => $validated['duration'],
            ]);

            // Criar payment
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'value' => $finalValue,
                'is_discounted' => $discount !== null,
                'is_accepted' => false,
                'payment_provider_ref' => $validated['payment_provider_ref'],
            ]);

            // Guardar em sessão
            session([
                'pending_booking_id' => $booking->id,
                'affected_schedule_ids' => collect($affectedSchedules['schedules'])->pluck('id')->toArray(),
                'number_of_persons' => $validated['number_of_persons'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'booking_id' => $booking->id,
                'payment' => [
                    'id' => $payment->id,
                    'value' => $payment->value,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking failed: ' . $e->getMessage());
            return response()->json(['error' => 'Booking failed'], 500);
        }
    }

    /**
     * Confirm payment
     */
    public function confirmPayment(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $bookingId = session('pending_booking_id');
        $scheduleIds = session('affected_schedule_ids');
        $persons = session('number_of_persons');

        if (!$bookingId || !$scheduleIds || !$persons) {
            return response()->json(['error' => 'No pending booking'], 400);
        }

        try {
            DB::beginTransaction();

            $booking = Booking::findOrFail($bookingId);
            $payment = Payment::where('booking_id', $bookingId)->firstOrFail();

            $payment->is_accepted = true;
            $payment->save();

            // Reduzir capacidade
            foreach ($scheduleIds as $scheduleId) {
                $schedule = Schedule::find($scheduleId);
                if ($schedule && !$schedule->reduceCapacity($persons)) {
                    DB::rollBack();
                    return response()->json(['error' => 'Insufficient capacity'], 400);
                }
            }

            session()->forget(['pending_booking_id', 'affected_schedule_ids', 'number_of_persons']);

            DB::commit();

            return response()->json(['success' => true], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment confirmation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Payment failed'], 500);
        }
    }

    /**
     * R407: Update booking
     */
    public function update(Request $request, $space_id, $schedule_id, $booking_id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'new_schedule_id' => 'required|integer|exists:schedule,id',
            'duration' => 'required|integer|min:30',
            'number_of_persons' => 'required|integer|min:1',
            'payment_provider_ref' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $booking = Booking::findOrFail($booking_id);

            $this->authorize('update', $booking);

            // Restaurar capacidades antigas
            $oldSchedules = $this->getAffectedSchedules(
                $booking->schedule,
                $booking->total_duration,
                $booking->number_of_persons
            );

            if ($oldSchedules['success']) {
                foreach ($oldSchedules['schedules'] as $schedule) {
                    $schedule->restoreCapacity($booking->number_of_persons);
                }
            }

            // Validar novos schedules
            $newSchedule = Schedule::findOrFail($validated['new_schedule_id']);
            $newSchedules = $this->getAffectedSchedules(
                $newSchedule,
                $validated['duration'],
                $validated['number_of_persons']
            );

            if (!$newSchedules['success']) {
                // Restaurar estado anterior
                foreach ($oldSchedules['schedules'] as $schedule) {
                    $schedule->reduceCapacity($booking->number_of_persons);
                }
                DB::rollBack();
                return response()->json(['error' => $newSchedules['message']], 400);
            }

            // Calcular novo valor
            $discount = Discount::where('space_id', $space_id)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            $newValue = Payment::calculateValue(
                $validated['duration'],
                $validated['number_of_persons'],
                $newSchedule->duration,
                $discount?->percentage
            );

            $oldPayment = $booking->payment;
            $priceDifference = $newValue - $oldPayment->value;

            // Atualizar booking
            $booking->update([
                'schedule_id' => $validated['new_schedule_id'],
                'number_of_persons' => $validated['number_of_persons'],
                'total_duration' => $validated['duration'],
            ]);

            if ($priceDifference > 0) {
                // Criar pagamento adicional
                $additionalPayment = Payment::create([
                    'booking_id' => $booking->id,
                    'value' => round($priceDifference, 2),
                    'is_discounted' => $discount !== null,
                    'is_accepted' => false,
                    'payment_provider_ref' => $validated['payment_provider_ref'],
                ]);

                session([
                    'pending_update_booking_id' => $booking->id,
                    'new_affected_schedule_ids' => collect($newSchedules['schedules'])->pluck('id')->toArray(),
                    'new_number_of_persons' => $validated['number_of_persons'],
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'requires_payment' => true,
                    'additional_payment' => $additionalPayment->value,
                ], 200);
            } else {
                // Sem pagamento adicional, reduzir capacidades
                foreach ($newSchedules['schedules'] as $schedule) {
                    $schedule->reduceCapacity($validated['number_of_persons']);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'requires_payment' => false,
                ], 200);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update failed: ' . $e->getMessage());
            return response()->json(['error' => 'Update failed'], 500);
        }
    }

    /**
     * R408: Cancel booking
     */
    public function cancel(Request $request, $space_id, $schedule_id, $booking_id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            DB::beginTransaction();

            $booking = Booking::findOrFail($booking_id);

            $this->authorize('cancel', $booking);

            // Obter schedules afetados
            $affectedSchedules = $this->getAffectedSchedules(
                $booking->schedule,
                $booking->total_duration,
                $booking->number_of_persons
            );

            // Cancelar
            $booking->is_cancelled = true;
            $booking->save();

            // Restaurar capacidades
            if ($affectedSchedules['success']) {
                foreach ($affectedSchedules['schedules'] as $schedule) {
                    $schedule->restoreCapacity($booking->number_of_persons);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled',
                'refund_amount' => $booking->payment->value,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cancellation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Cancellation failed'], 500);
        }
    }

    /**
     * Get available schedules
     */
    public function getAvailableSchedules(Request $request, $space_id)
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        $schedules = Schedule::where('space_id', $space_id)
            ->whereDate('start_time', $validated['date'])
            ->where('start_time', '>', now())
            ->where('max_capacity', '>', 0)
            ->orderBy('start_time')
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'start_time' => $s->start_time->format('H:i'),
                'duration' => $s->duration,
                'available_capacity' => $s->max_capacity,
            ]);

        return response()->json(['schedules' => $schedules], 200);
    }

    // Helper
    private function getAffectedSchedules($initialSchedule, $duration, $persons): array
    {
        $schedules = [];
        $current = $initialSchedule;
        $remaining = $duration;

        if ($current->max_capacity < $persons) {
            return ['success' => false, 'message' => 'Insufficient capacity'];
        }

        $schedules[] = $current;
        $remaining -= $current->duration;

        while ($remaining > 0) {
            $nextTime = $current->start_time->copy()->addMinutes($current->duration);
            $next = Schedule::where('space_id', $current->space_id)
                ->where('start_time', $nextTime)
                ->first();

            if (!$next || $next->max_capacity < $persons) {
                return ['success' => false, 'message' => 'No consecutive schedules available'];
            }

            $schedules[] = $next;
            $remaining -= $next->duration;
            $current = $next;
        }

        return ['success' => true, 'schedules' => $schedules];
    }
}
