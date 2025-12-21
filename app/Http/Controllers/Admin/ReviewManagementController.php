<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class ReviewManagementController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Load reviews with the reviewer and the associated space/service
        $reviews = Review::with(['customer.user', 'booking.space'])->orderBy('id', 'asc')->get();

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $review = Review::with(['user', 'space'])->findOrFail($id);

        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully.');
    }
}
