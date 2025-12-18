@extends('layouts.app')

@section('content')
    <div class="container mx-auto my-8 items-center">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center gap-2 mb-6 text-lg">
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
                    My Sport Spaces
                </p>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Select a Space</h1>
            <p class="text-gray-600 mb-8">Choose one of your sports spaces to manage reservations</p>

            @if($spaces->isEmpty())
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg">
                    <p>You don't have any sports spaces yet. <a href="{{ route('spaces.create') }}" class="underline font-semibold">Create one now</a></p>
                </div>
            @else
                <div class="flex overflow-x-auto gap-4">
                    @foreach($spaces as $space)
                        @include('spaces.partials.small-space-card', ['space'=>$space])
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
