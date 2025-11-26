<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Space;
use App\Models\SportType;

class SearchController
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        $exactSpaces = Space::where('title', $query)->orWhere('address', $query)->get();

        $partialSpaces = Space::where('title', 'ILIKE', "%{$query}%")->orwhere('address', 'ILIKE', "%{$query}%")->get();

        $spaces = $exactSpaces->merge($partialSpaces)->unique('id');

        return view('spaces.index', compact('spaces', 'query'));
    }
}
