<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Space;

class HomeController
{
    public function index()
    {
        $spaces = Space::orderby ('num_favorites', 'desc') -> take(4) -> get();
        return view('pages.home', compact('spaces'));
    }
}
