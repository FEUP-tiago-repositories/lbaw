{{-- -Page dedicated to Showing the full page of a Space, including Editing and Deleting a space (If a BO) and if user
making a booking --}}
@extends('layouts.app')
@section('title', $space->title . ' - Sports Hub')
@section('content')
    <main class="container mx-auto px-8 py-8">
        {{-- -Info Section --}}
        <section class="mx-auto">
            {{-- Div that will be used for Buttons to Delete and Edit space --}}
            <div class="flex justify-between">
                <h1 class="text-3xl font-bold mb-6">{{ $space->title }}</h1>
                {{-- -Delete and Edit buttons --}}
                <div class="flex gap-2.5">
                    {{-- -Delete Button --}}
                    <form action="{{ route('spaces.destroy', $space->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this space?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-1 bg-red-600 rounded-md hover:bg-red-700 transition">
                            <p class="text-white">Delete</p>
                        </button>
                    </form>
                    {{-- -Edit Button --}}
                    <a href="{{ route('spaces.edit', $space->id) }}"
                        class="px-4 py-1 bg-green-600 rounded-md hover:bg-green-700 transition flex items-center">
                        <p class="text-white">Edit</p>
                    </a>
                </div>
            </div>
            {{-- -Address flex --}}
            <div class="flex gap-3.5">
                <p class="text-2xl font-bold">Address: </p>
                <p class="text-2xl underline">{{ $space->address }}</p>
            </div>
            {{-- -Sport Type flex --}}
            <div class="flex gap-3.5">
                <p class="text-2xl font-bold">Sport Type: </p>
                <p class="text-2xl underline">{{ $space->sportType->name }}</p>
            </div>
            {{-- Images --}}
            <div class="flex gap-4 mt-4">
                @for ($i = 0; $i < 3; $i++)
                    <div class="flex flex-col items-center">
                        <img src="{{ asset('images/sportsplace.jpg') }}"
                            class="max-w-xs w-full h-auto rounded-lg mx-auto border-2" alt="Space Image {{ $i + 1 }}">
                        <span class="text-sm text-gray-600 mt-2">Fig. {{ $i + 1 }}</span>
                    </div>
                @endfor
            </div>
            {{-- Section About and Reviews and Calendar --}}
            <div class="flex gap-4 mt-6">
                {{-- Section About and Revies, will have JS behaviour --}}
                <div>
                    <div class="flex border-b-2 border-gray-200 pb-2.5">
                        <h2 class="text-xl font-medium border-r-2 pr-2.5 text-green-700">About</h2>
                        <h2 class="text-xl font-medium pl-2.5 hover:text-green-700">Reviews</h2>
                    </div>
                    <p class="text-base mt-3.5">{{ $space->description}}</p>
                </div>
            </div>
        </section>
    </main>
@endsection