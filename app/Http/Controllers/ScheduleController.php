<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Get available schedules for a specific date
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
                'duration' => $schedule->duration,
                'available_capacity' => $schedule->max_capacity
            ]);

        return response()->json($schedules);
    }

    /**
     * Get consecutive schedules for a booking duration
     * Static helper method used by BookingController
     */
    public static function getAffectedSchedules(Schedule $initialSchedule, int $duration, int $persons): array
    {
        $schedules = [];
        $current = $initialSchedule;
        $remaining = $duration;

        // Verificar capacidade inicial
        if (!$current->hasAvailableCapacity($persons)) {
            return [
                'success' => false,
                'message' => 'Insufficient capacity for the selected time slot'
            ];
        }

        $schedules[] = $current;
        $remaining -= $current->duration;

        // Buscar horários consecutivos
        while ($remaining > 0) {
            $nextTime = $current->start_time->copy()->addMinutes($current->duration);

            $next = Schedule::where('space_id', $current->space_id)
                ->where('start_time', $nextTime)
                ->first();

            if (!$next) {
                return [
                    'success' => false,
                    'message' => 'No consecutive time slots available for this duration'
                ];
            }

            if (!$next->hasAvailableCapacity($persons)) {
                return [
                    'success' => false,
                    'message' => 'Insufficient capacity in consecutive time slots'
                ];
            }

            $schedules[] = $next;
            $remaining -= $next->duration;
            $current = $next;
        }

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

    /**
     * Store a new schedule (for owners)
     * POST /api/space/{space_id}/schedule
     */
    public function store(Request $request, $space_id)
    {
        $validated = $request->validate([
            'start_time' => 'required|date',
            'duration' => 'required|integer|min:15',
            'max_capacity' => 'required|integer|min:1'
        ]);

        $schedule = Schedule::create([
            'space_id' => $space_id,
            'start_time' => $validated['start_time'],
            'duration' => $validated['duration'],
            'max_capacity' => $validated['max_capacity']
        ]);

        return response()->json([
            'success' => true,
            'schedule' => $schedule
        ], 201);
    }

    /**
     * Update schedule
     * PATCH /api/space/{space_id}/schedule/{schedule_id}
     */
    public function update(Request $request, $space_id, $schedule_id)
    {
        $schedule = Schedule::where('space_id', $space_id)
            ->findOrFail($schedule_id);

        $validated = $request->validate([
            'start_time' => 'sometimes|date',
            'duration' => 'sometimes|integer|min:15',
            'max_capacity' => 'sometimes|integer|min:0'
        ]);

        $schedule->update($validated);

        return response()->json([
            'success' => true,
            'schedule' => $schedule
        ]);
    }

    /**
     * Delete schedule
     * DELETE /api/space/{space_id}/schedule/{schedule_id}
     */
    public function destroy($space_id, $schedule_id)
    {
        $schedule = Schedule::where('space_id', $space_id)
            ->findOrFail($schedule_id);

        // Verificar se tem bookings
        if ($schedule->bookings()->exists()) {
            return response()->json([
                'error' => 'Cannot delete schedule with existing bookings'
            ], 400);
        }

        $schedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Schedule deleted successfully'
        ]);
    }
}
