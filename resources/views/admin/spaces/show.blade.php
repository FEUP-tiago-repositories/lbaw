@extends('layouts.admin')

@section('title', 'Space Details')

@section('content')
<main class="container mx-auto px-8 py-6">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm mb-4 text-gray-600">
        <a href="{{ route('admin.spaces.index') }}" class="hover:text-emerald-600 font-medium">
            Spaces
        </a>
        <span>/</span>
        <span class="text-gray-800 font-semibold">
            {{ $space->title }}
        </span>
    </div>

    {{-- Header + Admin Actions --}}
    <div class="flex justify-between items-start mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            {{ $space->title }}
        </h1>

        {{-- Admin Actions --}}
        <div class="flex gap-3">

            {{-- Close / Reopen --}}
            @if(!$space->is_closed)
                <form action="{{ route('admin.spaces.close', $space->id) }}" method="POST">
                    @csrf
                    <button class="px-5 py-2 bg-yellow-500 text-white rounded-lg font-semibold hover:bg-yellow-600 transition">
                        Close Space
                    </button>
                </form>
            @else
                <form action="{{ route('admin.spaces.reopen', $space->id) }}" method="POST">
                    @csrf
                    <button class="px-5 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
                        Reopen Space
                    </button>
                </form>
            @endif

            {{-- Delete --}}
            <form action="{{ route('admin.spaces.destroy', $space->id) }}" method="POST"
                  onsubmit="return confirm('Delete this space permanently?');">
                @csrf
                @method('DELETE')
                <button class="px-5 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition">
                    Delete
                </button>
            </form>
        </div>
    </div>

    {{-- Space Info --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">

        <div class="space-y-2">
            <p><strong>Address:</strong> {{ $space->address }}</p>
            <p><strong>Sport Type:</strong> {{ $space->sportType->name }}</p>
            <p><strong>Email:</strong> {{ $space->email }}</p>
            <p><strong>Phone:</strong> {{ $space->phone_no }}</p>
            <p>
                <strong>Status:</strong>
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    {{ $space->is_closed ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                    {{ $space->is_closed ? 'Closed' : 'Open' }}
                </span>
            </p>
        </div>

        <div class="space-y-2">
            <p><strong>Owner:</strong>
                {{ $space->owner->user->first_name ?? $space->owner->user->user_name }}
                {{ $space->owner->user->surname ?? '' }}
            </p>
            <p><strong>Favorites:</strong> {{ $space->num_favorites }}</p>
            <p><strong>Reviews:</strong> {{ $space->num_reviews }}</p>
            <p><strong>Total Rating:</strong> {{ $space->current_total_rating }}</p>
        </div>
    </div>

    {{-- Images --}}
    <div class="mt-8">
        <h2 class="text-xl font-semibold mb-3">Images</h2>

        @if($space->media->isNotEmpty())
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($space->media as $media)
                    <img src="{{ $media->media_url }}"
                         alt="space image"
                         class="w-full h-40 object-cover rounded-lg border">
                @endforeach
            </div>
        @else
            <p class="text-red-500 font-medium">No images uploaded.</p>
        @endif
    </div>

    {{-- Description --}}
    <div class="mt-8">
        <h2 class="text-xl font-semibold mb-2">Description</h2>
        <p class="text-gray-700">
            {{ $space->description }}
        </p>
    </div>

    {{-- Location --}}
    <div class="mt-8">
        <h2 class="text-xl font-semibold mb-2">Location</h2>
        {{ $space->address }}
    </div>

</main>
@endsection
