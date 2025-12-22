<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->businessOwner) { abort(403); }
        $ownerId = $user->businessOwner->id;

        $discounts = Discount::with('space') 
            ->whereHas('space', function($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->orderBy('start_date', 'desc')
            ->get();

        $Spaces = Space::where('owner_id', $ownerId)->pluck('title', 'id');

        return view('discounts.index', compact('discounts', 'Spaces'));
    }

    public function store(Request $request)
    {
        $ownerId = Auth::user()->businessOwner->id;

        $validated = $request->validate([
            'space_id'   => 'required|exists:space,id',
            'code'       => 'nullable|string|max:20|unique:discount,code',
            'percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
        ]);

        $space = Space::where('id', $validated['space_id'])
                      ->where('owner_id', $ownerId)
                      ->firstOrFail();

        Discount::create($validated);

        return back()->with('success', 'Discount successfully created!');
    }

    public function update(Request $request, Discount $discount)
    {
        if ($discount->space->owner_id !== Auth::user()->businessOwner->id) { abort(403); }

        $validated = $request->validate([
            'space_id'   => 'required|exists:space,id',
            'code'       => 'nullable|string|max:20|unique:discount,code,' . $discount->id,
            'percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
        ]);

        $newSpace = Space::where('id', $validated['space_id'])
                         ->where('owner_id', Auth::user()->businessOwner->id)
                         ->firstOrFail();

        $discount->update($validated);

        return back()->with('success', 'Discount updated!');
    }

    public function destroy(Discount $discount)
    {
        if ($discount->space->owner_id !== Auth::user()->businessOwner->id) { abort(403); }
        $discount->delete();
        return back()->with('success', 'Discount removed.');
    }
}