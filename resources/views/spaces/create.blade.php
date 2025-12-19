@extends('layouts.app')
@section('title', 'Create New Space - Sports Hub')

@section('content')
    <div class="container mx-auto px-8 py-8 max-w-4xl bg-white mt-2.5 rounded-3xl shadow-2xl">
        <div class = "flex justify-between items-center">
            <h1 class="text-3xl font-bold mb-6">Create New Space</h1>

            <button type="button" onclick="toggleModal()" 
                class="w-12 h-12 rounded-full bg-emerald-700 text-white font-bold flex items-center justify-center hover:bg-emerald-500 transition shadow-lg">
                ?
            </button>
        </div>

        <div id="helpModal" class="fixed inset-0 bg-transparent bg-opacity-60 flex items-center justify-center z-50 hidden backdrop-blur-sm transition-opacity duration-300">
            
            <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-3xl w-full min-h-[500px] relative transform transition-all scale-100 mx-4">
                <div class="text-gray-600 text-center mb-8 leading-relaxed">
                    @include('partials.help.create_space')
                </div>

                <div class="flex justify-center">
                    <button onclick="toggleModal()" class="px-8 py-3 bg-red-600 text-white rounded-full font-semibold shadow-lg hover:bg-red-700 hover:shadow-xl transition transform hover:-translate-y-0.5">
                        Close
                    </button>
                </div>
            </div>
        </div>

        {{-- Display errors, if any exist --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- The rest of the page will be a form section --}}
        <form action="{{ route('spaces.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- Title --}}
            <div class="mb-4">
                <label for="title" class="block text-lg font-medium mb-2">Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 bg-gray-200 shadow-gray-200 shadow"
                    required maxlength="100" placeholder="e.g., Downtown Basketball Court">
                <p class="text-sm text-gray-500 mt-1">Maximum 100 characters</p>
            </div>

            {{-- Sport Type --}}
            <div class="mb-4">
                <label for="sport_type_id" class="block text-lg font-medium mb-2">Sport Type *</label>
                <select name="sport_type_id" id="sport_type_id"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 bg-gray-200 shadow-gray-200 shadow"
                    required>
                    <option value="">Select a sport type</option>
                    @foreach($sportTypes as $sportType)
                        <option value="{{ $sportType->id }}" {{ old('sport_type_id') == $sportType->id ? 'selected' : '' }}>
                            {{ $sportType->name }}
                        </option>
                    @endforeach
                </select>

            </div>

            {{-- Address --}}
            <div class="mb-4">
                <label for="address" class="block text-lg font-medium mb-2">Address *</label>
                <input type="text" name="address" id="address" value="{{ old('address') }}"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 bg-gray-200 shadow-gray-200 shadow"
                    required maxlength="150" placeholder="e.g., Rua das Flores 123, Porto">
                <p class="text-sm text-gray-500 mt-1">Maximum 150 characters</p>
            </div>

            {{-- Description --}}
            <div class="mb-4">
                <label for="description" class="block text-lg font-medium mb-2">Description *</label>
                <textarea name="description" id="description"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 bg-gray-200 shadow-gray-200 shadow"
                    rows="4" required maxlength="300"
                    placeholder="Describe your space...">{{ old('description') }}</textarea>
                <p class="text-sm text-gray-500 mt-1">Maximum 300 characters</p>
            </div>

            {{-- Phone Number --}}
            <div class="mb-4">
                <label for="phone_no" class="block text-lg font-medium mb-2">Phone Number *</label>
                <input type="tel" name="phone_no" id="phone_no" value="{{ old('phone_no') }}"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 bg-gray-200 shadow-gray-200 shadow"
                    required maxlength="15" pattern="[0-9]{9,15}" placeholder="e.g., 912345678">
                <p class="text-sm text-gray-500 mt-1">Format: 9 to 15 digits</p>
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label for="email" class="block text-lg font-medium mb-2">Email *</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green- bg-gray-200 shadow-gray-200 shadow"
                    required maxlength="150" placeholder="e.g., space@example.com">
            </div>
            
            {{-- Cover Picture --}}
            <div class="mt-4">
                <label class="block text-lg font-medium mb-2">Cover Picture *</label>
                <input type="file" name="cover_image"
                    class="w-full rounded-lg px-4 py-2 bg-gray-200"
                    accept="image/*" required>
            </div>

            {{-- Other Pictures --}}
            <div class="mt-4">
                <label class="block text-lg font-medium mb-2">Other Pictures</label>
                <input type="file" name="gallery_images[]"
                    class="w-full rounded-lg px-4 py-2 bg-gray-200"
                    accept="image/*" multiple>
            </div>


            {{-- Action Buttons --}}
            <div class="flex gap-4 mt-6">
                <button type="submit"
                    class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-medium cursor-pointer hover:shadow">
                    Create Space
                </button>
                <a href="{{ route('spaces.index') }}"
                    class="px-6 py-3 bg-white text-back rounded-lg hover:bg-gray-100 transition font-medium border-2 border-black hover:shadow">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection