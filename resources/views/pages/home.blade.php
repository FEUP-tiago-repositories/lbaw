@extends('layouts.app')

@section('content')
<div class = "container mx-auto px-8 py-8">

    {{-- Mapa com todos os espaços --}}
    <div class="bg-emerald-900 rounded-lg shadow-xl p-2">
        <h2 class="flex items-center font-bold text-white mb-2 p-2">
            <svg class="w-8 h-8 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Explore Spaces Near You
        </h2>

        @php
            $spaces = \App\Models\Space::all();
            $markers = $spaces->map(function($space) {
                return [
                    'lat' => $space->latitude ?? 41.1579,
                    'lng' => $space->longitude ?? -8.6291,
                    'popup' => '<div class="text-center">
                                    <div class="font-bold text-base mb-2" style="color: #1f2937;">
                                        ' . e($space->title) . '
                                    </div>
                                    <a href="' . route('spaces.show', $space->id) . '"
                                       class="text-blue-600 hover:underline">
                                        View Details
                                    </a>
                                </div>'
                ];
            })->toArray();
        @endphp

        @include('partials.map', [
            'mapId' => 'homeMap',
            'markers' => $markers,
            'zoom' => 12,
            'height' => 'h-[300px]'
        ])

    </div>
    
    <h2 class = "mb-4 mt-4 text-3xl font-semibold">Best Reviewed:</h2>
    
    <div class="flex overflow-x-auto gap-2">
        @foreach ($spaces as $space)
            @include('spaces.partials.space-card', ['space' => $space])
        @endforeach
    </div>
</div>
@endsection
