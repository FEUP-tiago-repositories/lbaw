<?php

namespace App\Http\Controllers;

use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Booking;
use App\Models\Review;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ResponseController
{
    use AuthorizesRequests;
        /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'review_id' => 'required|exists:review,id',
            'text' => 'required|string|max:500',
        ]);

        $review = Review::findOrFail($validatedData['review_id']);

        // if user can even create a response
        $this->authorize('create',Response::class);

        // check if user can respond to the specific review
        if(!Gate::allows('createForReview',[Response::class,$review])){
            abort(403, 'You cannot respond to this review.');
        }


        //create response
        Response::create([
            'owner_id' => auth()->user()->businessOwner->id,
            'review_id' => $validatedData['review_id'],
            'text' => $validatedData['text'],
            'time_stamp' => now(),
        ]);

        return redirect()->route('spaces.show',$review->booking->space_id)->with('success','Response submitted successfully!');
    }
}
