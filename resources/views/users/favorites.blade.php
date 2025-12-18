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
            <div class="w-full flex justify-center">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 place-items-center">
                    @foreach ($favoritedSpaces as $favoritedSpace)
                        @include('users.partials.space-card-favorites', ['space' => $favoritedSpace])
                    @endforeach
                </div>
            </div>
        </section>
    </div>
@endsection