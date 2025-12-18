<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Space;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */

    /*
    * Toggle favorite status for a space, like a heart button
    */
    public function toggle(Request $request, Space $space)
    {
        // chech authorization using Favorite Policy
        $this->authorize('favorite', $space);

        $customer = Auth::user()->customer;

        // check if already favorited
        $favorite = Favorite::where('space_id', $space->id)->where('customer_id', $customer->id)->first();

        if ($favorite) {
            // toggle the is_favorite
            $favorite->delete();
            $message = 'Space unfavorited';
            $isFavorite = false;
        } else {
            // create new favorite record
            Favorite::create([
                'space_id' => $space->id,
                'customer_id' => $customer->id,
            ]);
            $message = 'Space Favorited';
            $isFavorite = true;
        }

        return response()->json([
            'message' => $message,
            'is_favorite' => $isFavorite,
        ]);
    }

    /**
     * Get all favorited spaces for the authenticated user
     */
    public function index()
    {
        $customer = Auth::user()->customer;
        if (! $customer) {
            abort(403, 'Only Customers can view favorite spaces!');
        }
        $favoritedSpaces = $customer->favoritedSpaces()->get();

        return view('users.favorites', compact('favoritedSpaces'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Favorite $favorite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Favorite $favorite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Favorite $favorite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Favorite $favorite)
    {
        //
    }
}
