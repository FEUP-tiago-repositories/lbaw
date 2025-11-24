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
        // fetch all spaces into the index view
        $spaces = Space::with(['sportType', 'media'])->orderBy('id', 'desc')->get(); // we will only need this info in the Space Card

        return view('spaces.index', compact('spaces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // display creation form /spaces/create
        return view('spaces.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // --> /spaces/ (POST)
    }

    /**
     * Display the specified resource.
     */
    public function show(Space $space)
    {
        // used for the route /space/{space}
        $space->load(['sportType', 'media', 'owner', 'coverImage']);

        return view('spaces.show', compact('space'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Space $space)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Space $space)
    {
        //
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
