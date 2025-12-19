<?php

namespace App\Http\Controllers;

use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Media;

class SpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // route --> /spaces/
        // all users have access to this page
        // $this->authorize('viewAny', Space::class);
        // fetch all spaces into the index view
        $spaces = Space::with(['sportType', 'media'])->orderBy('id', 'desc')->get(); // we will only need this info in the Space Card

        return view('spaces.index', compact('spaces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        if (! auth()->user()->businessOwner()) {
            abort(403, 'Only business owners can create a space!');
        }
        // Get all SportTypes for the drop down menu
        $sportTypes = \App\Models\SportType::all();
        // display creation form /spaces/create

        // // pass all BO with relation with user
        // $businessOwners = \App\Models\BusinessOwner::with('user')->get();

        return view('spaces.create', compact('sportTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (! auth()->user()->businessOwner) {
            abort(403, 'Only business owners can create spaces.');
        }
        // --> /spaces/ (POST)
        $validatedData = $request->validate([
            'sport_type_id' => 'required|exists:sport_type,id',
            'title' => 'required|string|max:100',
            'address' => 'required|string|max:150',
            'description' => 'required|string|max:300',
            'phone_no' => 'required|string|max:15',
            'email' => 'required|email|max:150',
            'cover_image' => 'nullable|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
        ]);

        $validatedData['owner_id'] = auth()->user()->businessOwner->id;

        // Create the space
        // num_favorites, num_reviews and ratings will automatically be 0
        $space = Space::create($validatedData);

        $basePath = "images/uploads/spaces/{$space->id}";
        $destination = public_path($basePath);

        if ($request->hasFile('cover_image')) {

            $cover = $request->file('cover_image');
            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }
            $coverName = 'cover_' . Str::uuid() . '.' . $cover->getClientOriginalExtension();
            $cover->move($destination, $coverName);
        
            Media::create([
                'space_id' => $space->id,
                'media_url' => "/{$basePath}/{$coverName}",
                'is_cover' => true,
            ]);
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
        
                $imageName = 'img_' . Str::uuid() . '.' . $image->getClientOriginalExtension();
        
                // SAME logic as cover
                $image->move($destination, $imageName);
        
                Media::create([
                    'space_id' => $space->id,
                    'media_url' => "/{$basePath}/{$imageName}",
                    'is_cover' => false,
                ]);
            }      
        }
        // Redirect to newly created space's show page!
        return redirect()->route('spaces.show', $space)->with('success', 'Space created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Space $space)
    {
        // used for the route /space/{space}
        $space->load(['sportType', 'media', 'owner.user', 'coverImage'/*, 'bookings.review.customer.user'/* 'bookings.review.responses' */]);

        // Get all reviews for this space
        $reviews = $space->reviews()
            ->with(['customer.user'/* 'responses.businessOwner.user' */,'booking'])
            ->orderBy('time_stamp', 'desc')
            ->get();

        // calculate ratings
        $totalReviews = $reviews->count();

        if ($totalReviews > 0) {
            $avgEnvironment = $reviews->avg('environment_rating');
            $avgEquipment = $reviews->avg('equipment_rating');
            $avgService = $reviews->avg('service_rating');
            $averageRating = ($avgEnvironment + $avgEquipment + $avgService) / 3;
        } else {
            $avgEnvironment = 0;
            $avgEquipment = 0;
            $avgService = 0;
            $averageRating = 0;
        }

        return view('spaces.show', compact('space', 'reviews', 'averageRating', 'avgEnvironment', 'avgEquipment', 'avgService', 'totalReviews'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Space $space)
    {
        // used to display the edit forms!
        if (! auth()->user()->businessOwner || auth()->user()->businessOwner->id !== $space->owner_id) {
            abort(403, 'You are not authorized to edit this space.');
        }

        // Load relationships needed
        $space->load(['sportType', 'media', 'coverImage']);

        // Get all SportTypes for the drop down menu
        $sportTypes = \App\Models\SportType::all();

        return view('spaces.edit', compact('space', 'sportTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Space $space)
    {
        if (! auth()->user()->businessOwner || auth()->user()->businessOwner->id !== $space->owner_id) {
            abort(403, 'You are not authorized to edit this space.');
        }

        // Validate input
        $validatedData = $request->validate([
            'title' => 'required|string|max:100',
            'sport_type_id' => 'required|exists:sport_type,id',
            'address' => 'required|string|max:150',
            'description' => 'required|string|max:300',
            'phone_no' => 'required|string|max:15',
            'email' => 'required|email|max:150',
            'is_closed' => 'nullable|boolean',
            'cover_image' => 'nullable|image|max:2048',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|max:2048',
            'delete_images' => 'nullable|array',
        ]);

        $validatedData['is_closed'] = $request->has('is_closed');

        $space->update($validatedData);

        $basePath = "images/uploads/spaces/{$space->id}";
        $destination = public_path($basePath);
        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }
    
        // cover image replacement
        if ($request->hasFile('cover_image')) {
            $cover = $request->file('cover_image');
    
            // Delete old cover if exists
            if ($space->coverImage && file_exists(public_path($space->coverImage->media_url))) {
                unlink(public_path($space->coverImage->media_url));
                $space->coverImage->delete();
            }
    
            $coverName = 'cover_' . Str::uuid() . '.' . $cover->getClientOriginalExtension();
            $cover->move($destination, $coverName);
    
            \App\Models\Media::create([
                'space_id' => $space->id,
                'media_url' => "/{$basePath}/{$coverName}",
                'is_cover' => true,
            ]);
        }
    
        // Add new gallery images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $imageName = 'img_' . Str::uuid() . '.' . $image->getClientOriginalExtension();
                $image->move($destination, $imageName);
    
                \App\Models\Media::create([
                    'space_id' => $space->id,
                    'media_url' => "/{$basePath}/{$imageName}",
                    'is_cover' => false,
                ]);
            }
        }
    
        // Delete selected gallery images
        if ($request->filled('delete_images')) {
            $images = \App\Models\Media::whereIn('id', $request->delete_images)
                ->where('space_id', $space->id)
                ->where('is_cover', false)
                ->get();
    
            foreach ($images as $img) {
                if (file_exists(public_path($img->media_url))) {
                    unlink(public_path($img->media_url));
                }
                $img->delete();
            }
        }
    
        // 8️⃣ Redirect back with success
        return redirect()->route('spaces.show', $space)
            ->with('success', 'Space updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Space $space)
    {
        if (! auth()->user()->businessOwner() || auth()->user()->businessOwner->id !== $space->owner_id) {
            abort(403, 'You are not allowed to delete this space!');
        }
        // used for /space/{spaces} to destroy the space
        $space->delete();

        return redirect()->route('spaces.index')->with('success', 'Space deleted successfully!');
    }
}
