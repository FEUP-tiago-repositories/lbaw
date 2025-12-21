<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Space;
use App\Models\Booking;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class HomeController
{
    public function index()
    {
        $spaces = Space::with('sportType')->orderBy('num_favorites', 'desc')->get();

        $recommendedSpaces = collect();
        $favoriteSpaces = collect();

        if (Auth::check() && Auth::user()->customer) {
            $customerId = Auth::user()->customer->id;

            $bookings = Booking::with('space.sportType')
                ->where('customer_id', $customerId)
                ->where('is_cancelled', false)
                ->get();

            if ($bookings->isNotEmpty()) {
                $topSportTypes = $bookings
                    ->groupBy(fn($b) => $b->space->sportType->id)
                    ->map->count()
                    ->sortDesc()
                    ->take(3)
                    ->keys();

                $recommendedSpaces = Space::with('sportType')
                    ->whereIn('sport_type_id', $topSportTypes)
                    ->take(10)
                    ->get();
            }

            $favorites = Favorite::with('space.sportType')
            ->where('customer_id', $customerId)
            ->get();

            if ($favorites->isNotEmpty()) {
                $topSportTypes = $favorites
                    ->groupBy(fn($b) => $b->space->sportType->id)
                    ->map->count()
                    ->sortDesc()
                    ->take(3)
                    ->keys();

                $favoriteSpaces = Space::with('sportType')
                    ->whereIn('sport_type_id', $topSportTypes)
                    ->take(10)
                    ->get();
            }
        }

        return view('pages.home', compact('spaces', 'recommendedSpaces','favoriteSpaces'));
    }
}
