@extends('layouts.app')
@section('title', 'Create New Space - Sports Hub')

@section('content')
    <div class="container mx-auto px-8 py-8 max-w-4xl bg-white mt-2.5 rounded-3xl shadow-2xl">
        <h1 class="text-3xl font-bold mb-6">Create New Space</h1>

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

            {{-- Business Owner Selection, temporary because I still dont have the logic for BO authentication --}}
            <div class="mb-6">
                <label for="owner_id" class="block text-lg font-medium mb-2">Business Owner *</label>
                <select name="owner_id" id="owner_id"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 bg-gray-200 shadow-gray-200 shadow"
                    required>
                    <option value="">Select a business owner</option>
                    @foreach($businessOwners as $owner)
                        <option value="{{ $owner->id }}" {{ old('owner_id') == $owner->id ? 'selected' : '' }}>
                            {{ $owner->user->user_name }} ({{ $owner->user->email }})
                        </option>
                    @endforeach
                </select>
                <p class="text-sm mt-1 text-red-400">This will be automatically set when authentication is
                    implemented</p>
            </div>

            {{-- Title --}}
            <div class="mb-6">
                <label for="title" class="block text-lg font-medium mb-2">Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 bg-gray-200 shadow-gray-200 shadow"
                    required maxlength="100" placeholder="e.g., Downtown Basketball Court">
                <p class="text-sm text-gray-500 mt-1">Maximum 100 characters</p>
            </div>

            {{-- Sport Type --}}
            <div class="mb-6">
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
            <div class="mb-6">
                <label for="address" class="block text-lg font-medium mb-2">Address *</label>
                <input type="text" name="address" id="address" value="{{ old('address') }}"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 bg-gray-200 shadow-gray-200 shadow"
                    required maxlength="150" placeholder="e.g., Rua das Flores 123, Porto">
                <p class="text-sm text-gray-500 mt-1">Maximum 150 characters</p>
            </div>

            {{-- Description --}}
            <div class="mb-6">
                <label for="description" class="block text-lg font-medium mb-2">Description *</label>
                <textarea name="description" id="description"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 bg-gray-200 shadow-gray-200 shadow"
                    rows="4" required maxlength="300"
                    placeholder="Describe your space...">{{ old('description') }}</textarea>
                <p class="text-sm text-gray-500 mt-1">Maximum 300 characters</p>
            </div>

            {{-- Phone Number --}}
            <div class="mb-6">
                <label for="phone_no" class="block text-lg font-medium mb-2">Phone Number *</label>
                <input type="tel" name="phone_no" id="phone_no" value="{{ old('phone_no') }}"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 bg-gray-200 shadow-gray-200 shadow"
                    required maxlength="15" pattern="[0-9]{9,15}" placeholder="e.g., 912345678">
                <p class="text-sm text-gray-500 mt-1">Format: 9 to 15 digits</p>
            </div>

            {{-- Email --}}
            <div class="mb-6">
                <label for="email" class="block text-lg font-medium mb-2">Email *</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green- bg-gray-200 shadow-gray-200 shadow"
                    required maxlength="150" placeholder="e.g., space@example.com">
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-4 mt-8">
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