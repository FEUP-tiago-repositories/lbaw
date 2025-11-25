<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Payment;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display user's bookings
     * GET /user/{user_id}/my_reservations
     */
    public function index($user_id)
    {
        // Permitir acesso em ambiente local sem autenticação
        if (!app()->environment('local')) {
            if (!Auth::check() || Auth::id() != $user_id) {
                return redirect()->route('login');
            }
        }

        $customer = Customer::where('user_id', $user_id)->firstOrFail();

        $bookings = Booking::where('customer_id', $customer->id)
            ->with(['space', 'schedule', 'payment']) // Garantir que payment é carregado
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
        // Permitir acesso em ambiente local
        if (!app()->environment('local') && !Auth::check()) {
            return redirect()->route('login');
        }

        $booking = Booking::with(['space', 'schedule', 'payment'])->findOrFail($booking_id);

        // Verificar permissão (exceto em local)
        if (!app()->environment('local')) {
            $this->authorize('update', $booking);
        }

        return view('bookings.edit', compact('booking'));
    }

    /**
     * Store a new booking
     * POST /api/space/{space_id}/schedule/{schedule_id}/bookings
     */
    public function store(Request $request, $space_id, $schedule_id)
    {
        $validated = $request->validate([
            'customer_id' => 'required|integer|exists:customer,id',
            'duration' => 'required|integer|min:30',
            'number_of_persons' => 'required|integer|min:1',
            'payment_provider_ref' => 'required|string|in:' . implode(',', Payment::PAYMENT_PROVIDERS)
        ]);

        DB::beginTransaction();

        try {
            $schedule = Schedule::findOrFail($schedule_id);

            // Verificar disponibilidade
            $result = ScheduleController::getAffectedSchedules(
                $schedule,
                $validated['duration'],
                $validated['number_of_persons']
            );

            if (!$result['success']) {
                return response()->json(['error' => $result['message']], 400);
            }

            // Calcular preço
            $totalPrice = $this->calculatePrice(
                $space_id,
                $validated['duration'],
                $validated['number_of_persons'],
                $schedule->duration
            );

            // Criar pagamento
            $payment = Payment::create([
                'value' => $totalPrice,
                'payment_provider_ref' => $validated['payment_provider_ref'],
                'is_accepted' => false, // Será confirmado depois
            ]);

            // Criar reserva
            $booking = Booking::create([
                'customer_id' => $validated['customer_id'],
                'schedule_id' => $schedule_id,
                'space_id' => $space_id,
                'total_duration' => $validated['duration'],
                'number_of_persons' => $validated['number_of_persons'],
                'payment_id' => $payment->id,
                'booking_created_at' => now(),
                'is_cancelled' => false
            ]);

            // Reservar capacidade
            ScheduleController::reserveCapacity($result['schedules'], $validated['number_of_persons']);

            DB::commit();

            return response()->json([
                'success' => true,
                'booking_id' => $booking->id,
                'payment' => [
                    'id' => $payment->id,
                    'value' => $payment->value
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create booking: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update an existing booking
     * PUT /api/space/{space_id}/schedule/{schedule_id}/bookings/{booking}
     */
    public function update(Request $request, $space_id, $schedule_id, $booking_id)
    {
        $booking = Booking::findOrFail($booking_id);

        // Verificar permissão em produção
        if (!app()->environment('local')) {
            $this->authorize('update', $booking);
        }

        $validated = $request->validate([
            'new_schedule_id' => 'required|integer|exists:schedule,id',
            'duration' => 'required|integer|min:30',
            'number_of_persons' => 'required|integer|min:1',
            'payment_provider_ref' => 'required|string'
        ]);

        DB::beginTransaction();

        try {
            // Restaurar capacidade antiga
            $oldResult = ScheduleController::getAffectedSchedules(
                $booking->schedule,
                $booking->total_duration,
                $booking->number_of_persons
            );

            if ($oldResult['success']) {
                ScheduleController::restoreCapacity($oldResult['schedules'], $booking->number_of_persons);
            }

            // Verificar novos horários
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

            // Calcular preços
            $newPrice = $this->calculatePrice(
                $space_id,
                $validated['duration'],
                $validated['number_of_persons'],
                $newSchedule->duration
            );

            $oldPrice = $booking->payment->value;
            $requiresPayment = $newPrice > $oldPrice;
            $additionalPayment = $requiresPayment ? ($newPrice - $oldPrice) : 0;

            // Atualizar ou criar pagamento
            if ($requiresPayment) {
                $payment = Payment::create([
                    'value' => $additionalPayment,
                    'payment_provider_ref' => $validated['payment_provider_ref'],
                    'is_accepted' => false,
                ]);
                $booking->payment_id = $payment->id;
            } else {
                $booking->payment->update(['value' => $newPrice]);
            }

            // Atualizar reserva
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

    /**
     * Cancel a booking
     * PATCH /api/space/{space_id}/schedule/{schedule_id}/bookings/{booking}/cancel
     */
    public function cancel($space_id, $schedule_id, $booking_id)
    {
        try {
            // Buscar booking com relacionamentos
            $booking = Booking::with(['schedule'])->findOrFail($booking_id);

            // Verificar permissão (ambiente local ignora)
            if (!app()->environment('local') && Auth::check()) {
                $this->authorize('cancel', $booking);
            }

            // Validar se já foi cancelada
            if ($booking->is_cancelled) {
                return response()->json([
                    'error' => 'Booking already cancelled'
                ], 400);
            }

            // Validar se é futura
            if (!$booking->isFuture()) {
                return response()->json([
                    'error' => 'Cannot cancel past bookings'
                ], 400);
            }

            DB::beginTransaction();

            // 1. Restaurar capacidade dos schedules afetados
            $result = ScheduleController::getAffectedSchedules(
                $booking->schedule,
                $booking->total_duration,
                $booking->number_of_persons
            );

            if ($result['success']) {
                ScheduleController::restoreCapacity($result['schedules'], $booking->number_of_persons);
            }

            // 2. Marcar booking como cancelada
            $booking->is_cancelled = true;
            $booking->save();

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

    /**
     * Calculate booking price with discount
     */
    private function calculatePrice(int $spaceId, int $duration, int $persons, int $scheduleDuration): float
    {
        $discount = Discount::where('space_id', $spaceId)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        return Payment::calculateValue($duration, $persons, $scheduleDuration, $discount?->percentage);
    }
}
