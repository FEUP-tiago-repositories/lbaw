<?php

namespace App\Http\Controllers;

use App\Models\Space;
use Illuminate\Http\Request;

class SpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // route --> /spaces/
        // all users have access to this page
        // $this->authorize('viewAny', Space::class);
        // fetch all spaces into the index view
        $spaces = Space::with(['sportType', 'media'])->orderBy('id', 'desc')->get(); // we will only need this info in the Space Card

        $sports = \App\Models\SportType::all();

        return view('spaces.index', compact('spaces', 'sports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if (! auth()->user()->businessOwner()) {
            abort(403, 'Only business owners can create a space!');
        }
        // Get all SportTypes for the drop down menu
        $sportTypes = \App\Models\SportType::all();
        // display creation form /spaces/create

        // // pass all BO with relation with user
        // $businessOwners = \App\Models\BusinessOwner::with('user')->get();

        return view('spaces.create', compact('sportTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (! auth()->user()->businessOwner) {
            abort(403, 'Only business owners can create spaces.');
        }
        // --> /spaces/ (POST)
        $validatedData = $request->validate([
            'sport_type_id' => 'required|exists:sport_type,id',
            'title' => 'required|string|max:100',
            'address' => 'required|string|max:150',
            'description' => 'required|string|max:300',
            'phone_no' => 'required|string|max:15',
            'email' => 'required|email|max:150',
        ]);

        $validatedData['owner_id'] = auth()->user()->businessOwner->id;

        // Create the space
        // num_favorites, num_reviews and ratings will automatically be 0
        $space = Space::create($validatedData);

        // Redirect to newly created space's show page!
        return redirect()->route('spaces.show', $space)->with('success', 'Space created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Space $space)
    {
        // used for the route /space/{space}
        $space->load(['sportType', 'media', 'owner.user', 'coverImage'/*, 'bookings.review.customer.user'/* 'bookings.review.responses' */]);

        // Get all reviews for this space
        $reviews = $space->reviews()
            ->with(['customer.user'/* 'responses.businessOwner.user' */,'booking'])
            ->orderBy('time_stamp', 'desc')
            ->get();

        // calculate ratings
        $totalReviews = $reviews->count();

        if ($totalReviews > 0) {
            $avgEnvironment = $reviews->avg('environment_rating');
            $avgEquipment = $reviews->avg('equipment_rating');
            $avgService = $reviews->avg('service_rating');
            $averageRating = ($avgEnvironment + $avgEquipment + $avgService) / 3;
        } else {
            $avgEnvironment = 0;
            $avgEquipment = 0;
            $avgService = 0;
            $averageRating = 0;
        }

        return view('spaces.show', compact('space', 'reviews', 'averageRating', 'avgEnvironment', 'avgEquipment', 'avgService', 'totalReviews'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Space $space)
    {
        // used to display the edit forms!
        if (! auth()->user()->businessOwner || auth()->user()->businessOwner->id !== $space->owner_id) {
            abort(403, 'You are not authorized to edit this space.');
        }

        // Load relationships needed
        $space->load(['sportType', 'media', 'coverImage']);

        // Get all SportTypes for the drop down menu
        $sportTypes = \App\Models\SportType::all();

        return view('spaces.edit', compact('space', 'sportTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Space $space)
    {
        if (! auth()->user()->businessOwner || auth()->user()->businessOwner->id !== $space->owner_id) {
            abort(403, 'You are not authorized to edit this space.');
        }

        // Validate input
        $validatedData = $request->validate([
            'title' => 'required|string|max:100',
            'sport_type_id' => 'required|exists:sport_type,id',
            'address' => 'required|string|max:150',
            'description' => 'required|string|max:300',
            'phone_no' => 'required|string|max:15',
            'email' => 'required|email|max:150',
            'is_closed' => 'nullable|boolean',
        ]);

        $validatedData['is_closed'] = $request->has('is_closed');

        $space->update($validatedData);

        return redirect()->route('spaces.show', $space)
            ->with('success', 'Space updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Space $space)
    {
        if (! auth()->user()->businessOwner() || auth()->user()->businessOwner->id !== $space->owner_id) {
            abort(403, 'You are not allowed to delete this space!');
        }
        // used for /space/{spaces} to destroy the space
        $space->delete();

        return redirect()->route('spaces.index')->with('success', 'Space deleted successfully!');
    }

    /**
     * Get space details for API (used for price calculation)
     * GET /api/space/{space_id}/details
     */
    public function getDetails($space_id)
    {
        try {
            $space = Space::findOrFail($space_id);

            return response()->json([
                'id' => $space->id,
                'title' => $space->title,
                'duration' => $space->duration ?? 30,  // Default to 30 if not set
                'opening_time' => $space->opening_time,
                'closing_time' => $space->closing_time
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Space not found'
            ], 404);
        }
    }
}