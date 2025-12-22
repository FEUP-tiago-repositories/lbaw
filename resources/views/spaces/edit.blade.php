@extends('layouts.app')
@section('title', 'Edit ' . $space->title . ' - Sports Hub')

@section('content')
<div class="container mx-auto px-8 py-8 max-w-4xl bg-white rounded-2xl shadow my-3.5">
    <div class = "flex justify-between items-center mb-2">
        <h1 class="text-3xl font-bold mb-6">Edit Space: {{ $space->title }}</h1>

        <button type="button" onclick="toggleModal()" 
            class="w-12 h-12 rounded-full bg-emerald-700 text-white font-bold flex items-center justify-center hover:bg-emerald-500 transition shadow-lg">
            ?
        </button>
    </div>

    <div id="helpModal" class="fixed inset-0 bg-transparent bg-opacity-60 flex items-center justify-center z-50 hidden backdrop-blur-sm transition-opacity duration-300">
            
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-3xl w-full min-h-[500px] relative transform transition-all scale-100 mx-4">
            <div class="text-gray-600 text-center mb-8 leading-relaxed">
                @include('partials.help.edit_space')
            </div>

            <div class="flex justify-center">
                <button onclick="toggleModal()" class="px-8 py-3 bg-red-600 text-white rounded-full font-semibold shadow-lg hover:bg-red-700 hover:shadow-xl transition transform hover:-translate-y-0.5">
                    Close
                </button>
            </div>
        </div>
    </div>

    {{-- Display errors --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Main form --}}
    <form action="{{ route('spaces.update', $space->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        {{-- Title --}}
        <div class="mb-4">
            <label for="title" class="block text-lg font-medium mb-2">Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title', $space->title) }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 bg-gray-300"
                required maxlength="100">
            <p class="text-sm text-gray-500 mt-1">Maximum 100 characters</p>
        </div>

        {{-- Sport Type --}}
        <div class="mb-4">
            <label for="sport_type_id" class="block text-lg font-medium mb-2">Sport Type <span class="text-red-500">*</span></label>
            <select name="sport_type_id" id="sport_type_id"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 bg-gray-300"
                required>
                @foreach($sportTypes as $sportType)
                    <option value="{{ $sportType->id }}" {{ old('sport_type_id', $space->sport_type_id) == $sportType->id ? 'selected' : '' }}>
                        {{ $sportType->name }}
                    </option>
                @endforeach
            </select>
            <p class="text-sm text-gray-500 mt-1">Select One</p>
        </div>

        {{-- Address --}}
        <div class="mb-4">
            <label for="address" class="block text-lg font-medium mb-2">Address <span class="text-red-500">*</span></label>
            <input type="text" name="address" id="address" value="{{ old('address', $space->address) }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 bg-gray-300"
                required maxlength="150">
        </div>

        {{-- Description --}}
        <div class="mb-4">
            <label for="description" class="block text-lg font-medium mb-2">Description <span class="text-red-500">*</span></label>
            <textarea name="description" id="description"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 bg-gray-300"
                rows="4" required maxlength="300">{{ old('description', $space->description) }}</textarea>
            <p class="text-sm text-gray-500 mt-1">Maximum 300 characters</p>
        </div>

        {{-- Phone Number --}}
        <div class="mb-4">
            <label for="phone_no" class="block text-lg font-medium mb-2">Phone Number <span class="text-red-500">*</span></label>
            <input type="tel" name="phone_no" id="phone_no" value="{{ old('phone_no', $space->phone_no) }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 bg-gray-300"
                required maxlength="15" pattern="[0-9]{9,15}">
            <p class="text-sm text-gray-500 mt-1">Format: 9 to 15 digits</p>
        </div>

        {{-- Email --}}
        <div class="mb-4">
            <label for="email" class="block text-lg font-medium mb-2">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" id="email" value="{{ old('email', $space->email) }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 bg-gray-300"
                required maxlength="150">
        </div>

        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2">Current Cover Image</h2>

            @if($space->coverImage)
                <img src="{{ $space->coverImage->media_url }}"
                    id="coverPreview"
                    alt="space cover image"
                    class="w-full h-56 object-cover rounded-xl mb-3">
            @else
                <p class="text-gray-500">No cover image</p>
            @endif

            <label class="block text-lg font-medium mb-2">Replace Cover Image</label>
            <input type="file" name="cover_image"
                data-preview="coverPreview"
                class="w-full rounded-lg px-4 py-2 bg-gray-200"
                accept="image/*">
        </div>
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-3">Gallery Images</h2>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($space->media->where('is_cover', false) as $image)
                    <div class="relative group" id="image-{{ $image->id }}">
                        <img src="{{ $image->media_url }}"
                            alt="space gallery image"
                            class="w-full h-32 object-cover rounded-lg shadow-sm">

                        {{-- Delete button overlay --}}
                        <label class="absolute inset-0 backdrop-blur-sm bg-opacity-30 text-white text-bg-black flex items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-pointer rounded-lg"
                          >
                            <input type="checkbox"
                                name="delete_images[]"
                                value="{{ $image->id }}"
                                data-delete-target="image-{{ $image->id }}"
                                class="hidden">
                            Delete?
                        </label>
                    </div>
                    
                @endforeach
            </div>
            </div>
        <div class="mb-6">
            <label class="block text-lg font-medium mb-2">Add More Pictures</label>
            <input type="file"
                name="gallery_images[]"
                multiple
                accept="image/*"
                data-gallery-preview="newGalleryPreview"
                class="w-full rounded-lg px-4 py-2 bg-gray-200">

            {{-- image preview --}}
            <div class="mt-4">
                <div
                    id="newGalleryPreview"
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                </div>
            </div>
        </div>


        {{-- Is Closed Status --}}
        <div class="mb-4">
            <label class="flex items-center">
                <input type="checkbox" 
                       name="is_closed" 
                       id="is_closed" 
                       value="1"
                       {{ old('is_closed', $space->is_closed) ? 'checked' : '' }}
                       class="w-5 h-5 text-green-600 border-gray-300 rounded focus:green-500">
                <span class="ml-2 text-lg">Mark space as closed</span>
            </label>
            <p class="text-sm text-red-500 mt-1">Warning: Marking as closed will anonymize space data (address, description, phone, email)</p>
        </div>

        {{-- Action Buttons --}}
        <div class="flex gap-4 mt-6">
            <button type="submit" 
                    class="px-6 py-3 bg-green-700 text-white rounded-lg transition font-medium hover:bg-green-400 cursor-pointer">
                Save Changes
            </button>
            <a href="{{ route('spaces.show', $space->id) }}" 
               class="px-6 py-3 bg-white text-black rounded-lg hover:bg-gray-200 transition-colors ease-in-out font-medium border-2 border-black">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
<script src="{{ asset('js/image-preview.js') }}"></script>
<script src="{{ asset('js/gallery-images.js') }}"></script>