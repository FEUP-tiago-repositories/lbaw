@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-start gap-2 mb-4 text-lg">
            <a href="{{ route('home') }}" class="text-emerald-600 hover:text-emerald-400">
                <img alt="Home Page" src="/images/home-icon.svg" height="18" width="18">
            </a>
            <svg class="w-5 h-5 pt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('users.show', Auth::id()) }}" class="text-emerald-600 hover:text-emerald-400">
                Profile of {{ Auth::user()->user_name }}
            </a>
            <svg class="w-5 h-5 pt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <p>
                My Favorites
            </p>
        </div>
        <h1 class="text-4xl font-bold mb-8">My Favorites</h1>
        {{-- Favorite spaces --}}
        <section>
            @if($favoritedSpaces->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <svg class="w-24 h-24 text-gray-300 mb-6" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    </svg>
                    <h2 class="text-2xl font-semibold text-gray-700 mb-2">No Favorites Yet!</h2>
                    <p class="text-gray-500">Start exploring and add your favorite spaces!</p>
                </div>
            @else
                <div class="w-full flex justify-center">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 place-items-center">
                        @foreach ($favoritedSpaces as $favoritedSpace)
                            @include('users.partials.space-card-favorites', ['space' => $favoritedSpace])
                        @endforeach
                    </div>
                </div>
            @endif
        </section>
    </div>
@endsection