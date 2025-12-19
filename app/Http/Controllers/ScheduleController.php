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
     * Get all schedules for a space (existing method)
     */
    public function index($space_id)
    {
        try {
            $space = Space::find($space_id);

            if (!$space) {
                return response()->json([
                    'success' => false,
                    'message' => 'Space not found'
                ], 404);
            }

            $schedules = Schedule::where('space_id', $space_id)
                ->orderBy('start_time')
                ->get();

            return response()->json([
                'success' => true,
                'schedules' => $schedules
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ScheduleController@index: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching schedules'
            ], 500);
        }
    }

    /**
     * Get schedule details (new method for modal)
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
     * Create a new schedule
     */
    public function store(Request $request, $space_id = null)
    {
        try {
            // Get space_id from route parameter or request body
            $spaceId = $space_id ?? $request->space_id;

            $validator = Validator::make(array_merge($request->all(), ['space_id' => $spaceId]), [
                'space_id' => 'required|exists:space,id',
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

            // Check if user owns this space
            $space = Space::find($spaceId);
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

            // Combine date and time
            $startTime = Carbon::parse($request->date . ' ' . $request->time);

            // Check if schedule already exists for this time
            $existingSchedule = Schedule::where('space_id', $spaceId)
                ->where('start_time', $startTime)
                ->first();

            if ($existingSchedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'A schedule already exists for this time slot'
                ], 422);
            }

            // Create schedule
            $schedule = Schedule::create([
                'space_id' => $spaceId,
                'start_time' => $startTime,
                'max_capacity' => $request->max_capacity
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Schedule created successfully',
                'schedule' => $schedule
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ScheduleController@store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the schedule'
            ], 500);
        }
    }

    /**
     * Update an existing schedule
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

            // Update schedule
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
     * Delete a schedule
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