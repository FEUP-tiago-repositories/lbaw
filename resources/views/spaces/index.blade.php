{{-- Will render the page with all sport spaces! --}}
@extends('layouts.app')
@section('title', 'Sport Spaces - Sports Hub')
@section('content')
    {{-- -Main section --}}
    <div class="container mx-auto px-8 py-8">
        {{-- Flex for Main tile and Add Space button! --}}
        <div class="flex justify-between">
            <h1 class="text-3xl font-bold mb-8">Take a look at our Spaces!</h1>
            @auth
                @if(auth()->user()->businessOwner)
                    {{-- Add space button --}}
                    <div
                        class="px-6 py-3 bg-emerald-800 text-white rounded-lg transition font-medium hover:bg-emerald-200 cursor-pointer mb-3.5">
                        <a class="text-white" href="{{ route('spaces.create') }}">+ Create a Space</a>
                    </div>
                @endif
            @endauth
        </div>
        <div class="flex">
            {{-- -This section will be used for the Spaces Grid --}}
            {{-- -We will use the predefined space card partial for each space --}}
            <div class="flex-[3] grid grid-cols-[repeat(auto-fit,minmax(450px,850px))] gap-4 items-start">
                @forelse ($spaces as $space)
                    @include('spaces.partials.space-card-horizontal', ['space' => $space])
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-xl">No spaces found.</p>
                    </div>
                @endforelse
            </div>
            {{-- Mapa com todos os espaços --}}
            <div class="flex-[2] sticky top-4 self-start h-[900px] bg-emerald-900 rounded-lg shadow-xl p-2 ">
                @php
                    $spaces = \App\Models\Space::all();
                    $markers = $spaces->map(function ($space) {
                        return [
                            'lat' => $space->latitude ?? 41.1579,
                            'lng' => $space->longitude ?? -8.6291,
                            'popup' => '<div class="text-center">
                                                        <div class="font-bold text-base mb-2" style="color: #1f2937;">
                                                            ' . e($space->title) . '
                                                        </div>
                                                        <a href="' . route('spaces.show', $space->id) . '"
                                                           class="text-emerald-800 hover:underline">
                                                            View Details
                                                        </a>
                                                    </div>'
                        ];
                    })->toArray();
                @endphp

                @include('partials.map', [
                    'mapId' => 'homeMap',
                    'markers' => $markers,
                    'zoom' => 15,
                    'height' => 'h-full'
                ])
            </div>
        </div>
    </div>
@endsection