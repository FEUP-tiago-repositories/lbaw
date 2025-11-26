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

        return view('spaces.index', compact('spaces'));
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
        $space->load(['sportType', 'media', 'owner.user', 'coverImage']);

        return view('spaces.show', compact('space'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Space $space)
    {
        // used to display the edit forms!

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
        // used for /space/{spaces} to destroy the space
        $space->delete();

        return redirect()->route('spaces.index')->with('success', 'Space deleted successfully!');
    }
}
