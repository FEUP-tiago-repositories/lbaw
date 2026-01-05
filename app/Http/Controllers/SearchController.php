<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Space;
use App\Models\SportType;

class SearchController
{
    public function search(Request $request)
    {

        $query = Space::query();
        
        if ($request->filled('sport_type')) {
            $query->whereHas('sportType', function($q) use ($request) {
                $q->whereIn('id', $request->input('sport_type'));
            });
        }

        if ($request->filled('date_from') || $request->filled('date_to') || $request->filled('capacity')) {
            
            $query->whereHas('schedules', function ($q) use ($request) {
                
                if ($request->filled('date_from')) {
                    $q->where('start_time', '>=', $request->input('date_from') . ' 00:00:00');
                }

                if ($request->filled('date_to')) {
                    $q->where('start_time', '<=', $request->input('date_to') . ' 23:59:59');
                }

                if ($request->filled('time_from')) {
                    $q->whereTime('start_time', '>=', $request->input('time_from'));
                }

                if ($request->filled('time_to')) {
                    $q->whereTime('start_time', '<=', $request->input('time_to'));
                }

                if ($request->filled('capacity')) {
                    $q->where('max_capacity', '>=', $request->input('capacity'));
                }
            });
        }

        if($request->filled('q')){
            
            $terms = preg_split('/\s+/', trim($request->q));

            $query->where(function ($campos) use ($terms) {

                foreach ($terms as $term) {
                    $campos->where(function ($camposSpace) use ($term) {

                        $camposSpace->where('title', 'ILIKE', "%{$term}%")
                        ->orWhere('address', 'ILIKE', "%{$term}%")
                        
                        ->orWhereHas('sportType', function ($stype) use ($term) {
                            $stype->where('name', 'ILIKE', "%{$term}%");
                        });
                    });
                }
            });
        }
        
        $spaces = $query->get(); 

        $sports = SportType::all();

        return view('spaces.index', ['spaces' => $spaces, 'sports'  => $sports, 'query' => $request->input('q'), 'filters' => $request->all() ]);
    }
}
