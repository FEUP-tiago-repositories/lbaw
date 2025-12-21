<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Get available schedules for a space on a specific date
     * Used by booking widget to show available time slots
     * GET /api/space/{space_id}/schedule?date=YYYY-MM-DD
     */
    public function index(Request $request, $space_id)
    {
        $validated = $request->validate([
            'date' => 'required|date'
        ]);

        $schedules = Schedule::where('space_id', $space_id)
            ->whereDate('start_time', $validated['date'])
            ->where('max_capacity', '>', 0)
            ->orderBy('start_time')
            ->get()
            ->map(fn($schedule) => [
                'id' => $schedule->id,
                'start_time' => $schedule->start_time->format('H:i'),
                'available_capacity' => $schedule->max_capacity
            ]);

        return response()->json($schedules);
    }

    /**
     * Get consecutive schedules for a booking duration
     * Static helper method used by BookingController
     *
     * NOTE: Duration is stored in Space, not in individual Schedules
     */
    public static function getAffectedSchedules(Schedule $initialSchedule, int $duration, int $persons): array
    {
        Log::info('getAffectedSchedules called', [
            'initial_schedule_id' => $initialSchedule->id,
            'requested_duration' => $duration,
            'persons' => $persons
        ]);

        // Get space to access duration
        $space = $initialSchedule->space;
        if (!$space) {
            Log::error('Schedule has no associated space', ['schedule_id' => $initialSchedule->id]);
            return [
                'success' => false,
                'message' => 'Schedule configuration error: no space found'
            ];
        }

        $slotDuration = $space->duration;

        // Safety check: space duration must be positive
        if (!isset($slotDuration) || $slotDuration <= 0) {
            Log::error('Space has invalid duration', [
                'space_id' => $space->id,
                'space_duration' => $slotDuration
            ]);
            return [
                'success' => false,
                'message' => 'Space configuration error: invalid slot duration'
            ];
        }

        // Safety check: requested duration must be positive
        if ($duration <= 0) {
            Log::error('Invalid requested duration', ['duration' => $duration]);
            return [
                'success' => false,
                'message' => 'Invalid booking duration'
            ];
        }

        Log::info('Using space slot duration', [
            'space_id' => $space->id,
            'slot_duration' => $slotDuration
        ]);

        $schedules = [];
        $current = $initialSchedule;
        $remaining = $duration;
        $iterations = 0;
        $maxIterations = 100; // Safety limit to prevent infinite loops

        // Verificar capacidade inicial
        if (!$current->hasAvailableCapacity($persons)) {
            Log::warning('Insufficient initial capacity', [
                'schedule_id' => $current->id,
                'available' => $current->max_capacity,
                'required' => $persons
            ]);
            return [
                'success' => false,
                'message' => 'Insufficient capacity for the selected time slot'
            ];
        }

        $schedules[] = $current;
        $remaining -= $slotDuration;

        Log::info('First schedule added', [
            'schedule_id' => $current->id,
            'slot_duration' => $slotDuration,
            'remaining' => $remaining
        ]);

        // Buscar horários consecutivos
        while ($remaining > 0) {
            $iterations++;

            // Safety check: prevent infinite loop
            if ($iterations > $maxIterations) {
                Log::error('Maximum iterations exceeded in getAffectedSchedules', [
                    'iterations' => $iterations,
                    'remaining' => $remaining,
                    'schedules_found' => count($schedules)
                ]);
                return [
                    'success' => false,
                    'message' => 'Unable to find consecutive time slots (too many iterations)'
                ];
            }

            $nextTime = $current->start_time->copy()->addMinutes($slotDuration);

            Log::info('Looking for next schedule', [
                'iteration' => $iterations,
                'current_schedule_id' => $current->id,
                'current_end_time' => $current->start_time->copy()->addMinutes($slotDuration)->format('H:i'),
                'next_time_looking_for' => $nextTime->format('H:i'),
                'remaining' => $remaining
            ]);

            $next = Schedule::where('space_id', $current->space_id)
                ->where('start_time', $nextTime)
                ->first();

            if (!$next) {
                Log::warning('No consecutive schedule found', [
                    'looking_for_time' => $nextTime->format('Y-m-d H:i:s'),
                    'space_id' => $current->space_id
                ]);
                return [
                    'success' => false,
                    'message' => 'No consecutive time slots available for this duration'
                ];
            }

            if (!$next->hasAvailableCapacity($persons)) {
                Log::warning('Next schedule has insufficient capacity', [
                    'schedule_id' => $next->id,
                    'available' => $next->max_capacity,
                    'required' => $persons
                ]);
                return [
                    'success' => false,
                    'message' => 'Insufficient capacity in consecutive time slots'
                ];
            }

            $schedules[] = $next;
            $remaining -= $slotDuration;
            $current = $next;

            Log::info('Schedule added to chain', [
                'schedule_id' => $next->id,
                'slot_duration' => $slotDuration,
                'remaining' => $remaining,
                'total_schedules' => count($schedules)
            ]);
        }

        Log::info('getAffectedSchedules successful', [
            'total_schedules' => count($schedules),
            'iterations' => $iterations
        ]);

        return ['success' => true, 'schedules' => $schedules];
    }

    /**
     * Reserve capacity in multiple schedules
     */
    public static function reserveCapacity(array $schedules, int $persons): void
    {
        foreach ($schedules as $schedule) {
            $schedule->reserveCapacity($persons);
        }
    }

    /**
     * Restore capacity in multiple schedules
     */
    public static function restoreCapacity(array $schedules, int $persons): void
    {
        foreach ($schedules as $schedule) {
            $schedule->restoreCapacity($persons);
        }
    }

    // ============================================
    // BUSINESS OWNER METHODS (for schedule modal)
    // ============================================

    /**
     * Get schedule details (for edit modal)
     * GET /api/schedules/{id}
     */
    public function show($id)
    {
        try {
            $schedule = Schedule::find($id);

            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Schedule not found'
                ], 404);
            }

            // Check if user owns this space
            $space = Space::find($schedule->space_id);
            if (!$space) {
                return response()->json([
                    'success' => false,
                    'message' => 'Space not found'
                ], 404);
            }

            if (!Auth::check() || !Auth::user()->businessOwner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: Business owner account required'
                ], 403);
            }

            if ($space->owner_id !== Auth::user()->businessOwner->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: You do not own this space'
                ], 403);
            }

            // Parse schedule data
            $startTime = Carbon::parse($schedule->start_time);

            return response()->json([
                'success' => true,
                'schedule' => [
                    'id' => $schedule->id,
                    'space_id' => $schedule->space_id,
                    'date' => $startTime->format('Y-m-d'),
                    'time' => $startTime->format('H:i'),
                    'max_capacity' => $schedule->max_capacity
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ScheduleController@show: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching schedule details'
            ], 500);
        }
    }

    /**
     * Store a new schedule (for owners)
     * POST /api/space/{space_id}/schedule
     *
     */
    public function store(Request $request, $space_id)
    {
        try {
            // Check if user owns this space
            $space = Space::find($space_id);
            if (!$space) {
                return response()->json([
                    'success' => false,
                    'message' => 'Space not found'
                ], 404);
            }

            if (!Auth::check() || !Auth::user()->businessOwner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: Business owner account required'
                ], 403);
            }

            if ($space->owner_id !== Auth::user()->businessOwner->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: You do not own this space'
                ], 403);
            }

            // Validation com campos opcionais
            $validator = Validator::make($request->all(), [
                'date' => 'required|date|after_or_equal:today',
                'time' => 'required_if:all_day,false',
                'max_capacity' => 'required|integer|min:1',
                'is_recurring' => 'boolean',
                'end_date' => 'required_if:is_recurring,true|date|after_or_equal:date',
                'all_day' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $isRecurring = $request->boolean('is_recurring');
            $allDay = $request->boolean('all_day');
            $createdCount = 0;
            $skippedCount = 0;

            // Determinar datas para processar
            $startDate = Carbon::parse($request->date);
            $endDate = $isRecurring ? Carbon::parse($request->end_date) : $startDate;

            // Determinar horários para processar
            $times = [];
            if ($allDay) {
                // Gerar todos os time slots do espaço
                $openingTime = Carbon::parse($space->opening_time);
                $closingTime = Carbon::parse($space->closing_time);
                $duration = $space->duration;

                $currentTime = $openingTime->copy();
                while ($currentTime->lt($closingTime)) {
                    $times[] = $currentTime->format('H:i');
                    $currentTime->addMinutes($duration);
                }
            } else {
                $times[] = $request->time;
            }

            // Criar schedules para cada combinação de data + hora
            $currentDate = $startDate->copy();

            while ($currentDate->lte($endDate)) {
                foreach ($times as $time) {
                    $startTime = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $time);

                    // Verificar se já existe
                    $existingSchedule = Schedule::where('space_id', $space_id)
                        ->where('start_time', $startTime)
                        ->first();

                    if ($existingSchedule) {
                        $skippedCount++;
                        continue;
                    }

                    // Criar schedule
                    Schedule::create([
                        'space_id' => $space_id,
                        'start_time' => $startTime,
                        'max_capacity' => $request->max_capacity
                    ]);

                    $createdCount++;
                }

                $currentDate->addDay();
            }

            // Mensagem de sucesso com detalhes
            $message = "Successfully created {$createdCount} schedule(s)";
            if ($skippedCount > 0) {
                $message .= " ({$skippedCount} skipped because they already exist)";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'created_count' => $createdCount,
                'skipped_count' => $skippedCount
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error in ScheduleController@store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the schedule(s)'
            ], 500);
        }
    }


    /**
     * Update schedule
     * PUT /api/schedules/{id} OR PATCH /api/space/{space_id}/schedule/{schedule_id}
     */
    public function update(Request $request, $space_id_or_id, $schedule_id = null)
    {
        try {
            // Determine which route structure is being used
            if ($schedule_id === null) {
                // Simple route: PUT /api/schedules/{id}
                $id = $space_id_or_id;
            } else {
                // Nested route: PATCH /api/space/{space_id}/schedule/{schedule_id}
                $id = $schedule_id;
            }

            $schedule = Schedule::find($id);

            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Schedule not found'
                ], 404);
            }

            // Check if user owns this space
            $space = Space::find($schedule->space_id);
            if (!$space) {
                return response()->json([
                    'success' => false,
                    'message' => 'Space not found'
                ], 404);
            }

            if (!Auth::check() || !Auth::user()->businessOwner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: Business owner account required'
                ], 403);
            }

            if ($space->owner_id !== Auth::user()->businessOwner->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: You do not own this space'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'date' => 'required|date|after_or_equal:today',
                'time' => 'required',
                'max_capacity' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Combine date and time
            $startTime = Carbon::parse($request->date . ' ' . $request->time);

            // Check if schedule already exists for this time (excluding current schedule)
            $existingSchedule = Schedule::where('space_id', $schedule->space_id)
                ->where('start_time', $startTime)
                ->where('id', '!=', $id)
                ->first();

            if ($existingSchedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'A schedule already exists for this time slot'
                ], 422);
            }

            // Update schedule (duration is in space, not in schedule)
            $schedule->update([
                'start_time' => $startTime,
                'max_capacity' => $request->max_capacity
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Schedule updated successfully',
                'schedule' => $schedule
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ScheduleController@update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the schedule'
            ], 500);
        }
    }

    /**
     * Delete schedule
     * DELETE /api/schedules/{id} OR DELETE /api/space/{space_id}/schedule/{schedule_id}
     */
    public function destroy($space_id_or_id, $schedule_id = null)
    {
        try {
            // Determine which route structure is being used
            if ($schedule_id === null) {
                // Simple route: DELETE /api/schedules/{id}
                $id = $space_id_or_id;
            } else {
                // Nested route: DELETE /api/space/{space_id}/schedule/{schedule_id}
                $id = $schedule_id;
            }

            $schedule = Schedule::find($id);

            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Schedule not found'
                ], 404);
            }

            // Check if user owns this space
            $space = Space::find($schedule->space_id);
            if (!$space) {
                return response()->json([
                    'success' => false,
                    'message' => 'Space not found'
                ], 404);
            }

            if (!Auth::check() || !Auth::user()->businessOwner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: Business owner account required'
                ], 403);
            }

            if ($space->owner_id !== Auth::user()->businessOwner->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: You do not own this space'
                ], 403);
            }

            // Check if schedule has bookings
            $hasBookings = $schedule->bookings()->exists();

            if ($hasBookings) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete schedule with existing bookings'
                ], 422);
            }

            $schedule->delete();

            return response()->json([
                'success' => true,
                'message' => 'Schedule deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ScheduleController@destroy: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the schedule'
            ], 500);
        }
    }
}