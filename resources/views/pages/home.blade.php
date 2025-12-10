@extends('layouts.app')

@section('content')
    {{-- Mapa com todos os espaços --}}
    <div class="container mx-auto bg-emerald-900 rounded-2xl shadow-xl py-4 px-8 mt-6 max-w-7xl">
        <h2 class="text-2xl flex items-center font-bold text-white p-2 mb-4">
            <svg class="w-8 h-8 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Explore Sports Spaces Near You
        </h2>
        <div class="h-[300px]">
            @include('partials.map', [
                'mapId' => 'homeMap',
                'spaces' => $spaces,
                'zoom' => 12,
                'showPopupImage' => true,
                'popupImageHeight' => 'h-24',
                'fitBoundsPadding' => 20
            ])
        </div>
    </div>


    <div class="container px-8 py-8 mx-auto max-w-7xl">
        <h2 class = "mb-4 text-3xl font-semibold">Best Reviewed:</h2>

        <div class="flex overflow-x-auto gap-4 pb-2">
            @foreach ($spaces as $space)
                <div class="shrink-0 max-w-80">
                    @include('spaces.partials.space-card', ['space' => $space])
                </div>
            @endforeach
        </div>
    </div>
@endsection
