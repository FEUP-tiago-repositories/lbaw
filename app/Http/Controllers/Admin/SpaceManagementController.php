<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Space;
use Illuminate\Http\Request;

class SpaceManagementController extends Controller
{
    /**
     * Display a listing of spaces.
     */
    public function index()
    {
        $spaces = Space::with(['owner.user', 'sportType'])
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.spaces.index', compact('spaces'));
    }

    /**
     * Remove the specified space.
     */
    public function destroy(string $id)
    {
        $space = Space::findOrFail($id);
        $space->delete();

        return redirect()->route('admin.spaces.index')
            ->with('success', 'Space deleted successfully.');
    }
}
