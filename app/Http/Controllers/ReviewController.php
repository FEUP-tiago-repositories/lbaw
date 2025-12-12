<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'booking_id' => 'required|exists:booking,id',
            'environment_rating' => 'required|integer|min:1|max:5',
            'equipment_rating' => 'required|integer|min:1|max:5',
            'service_rating' => 'required|integer|min:1|max:5',
            'text' => 'required|string|max:500',
        ]);

        // booking belongs to auth user
        $booking = Booking::findOrFail($validatedData['booking_id']);

        if ($booking->customer_id !== auth()->user()->customer->id) {
            abort(403, 'Unauthorized');
        }

        // check if review already exists
        if ($booking->review) {
            return redirect()->back()->with('error', 'You have already reviewed this booking');
        }

        // create a review
        Review::create([
            'booking_id' => $validatedData['booking_id'],
            'customer_id' => auth()->user()->customer->id,
            'environment_rating' => $validatedData['environment_rating'],
            'equipment_rating' => $validatedData['equipment_rating'],
            'service_rating' => $validatedData['service_rating'],
            'text' => $validatedData['text'],
            'time_stamp' => now(),
        ]);

        return redirect()->route('spaces.show', $booking->space_id)->with('success', 'Review submitted successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
