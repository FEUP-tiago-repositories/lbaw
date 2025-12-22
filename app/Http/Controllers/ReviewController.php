<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReviewController
{
    use AuthorizesRequests;
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
        if (!auth()->check() || !auth()->user()->customer) {
            abort(403, 'Only customers can submit reviews.');
        }

        $validatedData = $request->validate([
            'booking_id' => 'required|integer|exists:booking,id',
            'environment_rating' => 'required|integer|min:1|max:5',
            'equipment_rating' => 'required|integer|min:1|max:5',
            'service_rating' => 'required|integer|min:1|max:5',
            'text' => 'required|string|max:500',
        ]);

        // check if user can create reviews
        $this->authorize('create',Review::class);

        // check if user can review this space
        if(!Gate::allows('createForBooking',[Review::class,$validatedData['booking_id']])){
            abort(403, 'You cannot review this booking.');
        }

        // booking belongs to auth user
        $booking = Booking::findOrFail($validatedData['booking_id']);


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
