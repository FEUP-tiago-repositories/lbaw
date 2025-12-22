@extends('layouts.app')

@section('content')
    {{-- Mapa com todos os espaços --}}
    <div class="container mx-auto bg-emerald-800 rounded-3xl shadow-xl py-2 px-2 mt-6 max-w-6xl">
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


    <div class="container px-8 py-8 mx-auto max-w-[1200px]">
        <h2 class = "mb-4 text-3xl font-semibold">Best Reviewed</h2>
        <div class="relative">
            <!-- Gradiente Esquerdo -->
            <div id="best-gradient-left" class="absolute left-0 top-0 bottom-0 w-24 bg-gradient-to-r from-gray-100 to-transparent z-10 pointer-events-none opacity-0 transition-opacity duration-300"></div>

            <!-- Seta Esquerda -->
            <button id="best-scroll-left" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white rounded-full p-3 shadow-lg hover:bg-gray-50 transition opacity-0 pointer-events-none">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <!-- Container com Scroll Horizontal -->
            <div id="best-scroll-container" class="flex overflow-x-auto gap-2 pb-4 scroll-smooth scrollbar-hide"
                 style="-ms-overflow-style: none; scrollbar-width: none;">
                @php
                    $sortedSpaces = $spaces->sortByDesc(function($space) {
                        return ($space->current_environment_rating +
                               $space->current_equipment_rating +
                               $space->current_service_rating) / 3;
                    });
                @endphp
                @foreach($sortedSpaces as $space)
                    <div class="shrink-0 w-[240px]">
                        @include('spaces.partials.space-card', ['space' => $space])
                    </div>
                @endforeach
            </div>

            <!-- Gradiente Direito -->
            <div id="best-gradient-right" class="absolute right-0 top-0 bottom-0 w-24 bg-gradient-to-l from-gray-100 to-transparent z-10 pointer-events-none transition-opacity duration-300"></div>

            <!-- Seta Direita -->
            <button id="best-scroll-right" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white rounded-full p-3 shadow-lg hover:bg-gray-50 transition">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
        <h2 class = "my-4 text-3xl font-semibold">Looking for a gym after the holiday season?</h2>
        <div class="relative">
            <!-- Gradiente Esquerdo -->
            <div id="gym-gradient-left" class="absolute left-0 top-0 bottom-0 w-24 bg-gradient-to-r from-gray-100 to-transparent z-10 pointer-events-none opacity-0 transition-opacity duration-300"></div>

            <!-- Seta Esquerda -->
            <button id="gym-scroll-left" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white rounded-full p-3 shadow-lg hover:bg-gray-50 transition opacity-0 pointer-events-none">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <!-- Container com Scroll Horizontal -->
            <div id="gym-scroll-container" class="flex overflow-x-auto gap-2 pb-4 scroll-smooth scrollbar-hide"
                 style="-ms-overflow-style: none; scrollbar-width: none;">
                @php
                    $gymSpaces = $spaces->where('sportType.name', 'Gym') ->sortByDesc(function($space) {
                        return ($space->current_environment_rating +
                               $space->current_equipment_rating +
                               $space->current_service_rating) / 3;
                    });
                @endphp
                @foreach($gymSpaces as $space)
                    <div class="shrink-0 w-[240px]">
                        @include('spaces.partials.space-card', ['space' => $space])
                    </div>
                @endforeach
            </div>

            <!-- Gradiente Direito -->
            <div id="gym-gradient-right" class="absolute right-0 top-0 bottom-0 w-24 bg-gradient-to-l from-gray-100 to-transparent z-10 pointer-events-none transition-opacity duration-300"></div>

            <!-- Seta Direita -->
            <button id="gym-scroll-right" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white rounded-full p-3 shadow-lg hover:bg-gray-50 transition">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
        @if($recommendedSpaces->isNotEmpty() || $favoriteSpaces->isNotEmpty())
            <h2 class="my-4 text-3xl font-semibold">Recommended For You</h2>
            <div class="relative mb-8">
                <div id="recommendations-gradient-left" class="absolute left-0 top-0 bottom-0 w-24 bg-gradient-to-r from-gray-100 to-transparent z-10 pointer-events-none opacity-0 transition-opacity duration-300"></div>

                <button id="recommendations-scroll-left" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white rounded-full p-3 shadow-lg hover:bg-gray-50 transition opacity-0 pointer-events-none">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <div id="recommendations-scroll-container" class="flex overflow-x-auto gap-2 pb-4 scroll-smooth scrollbar-hide"
                     style="-ms-overflow-style: none; scrollbar-width: none;">
                    @php
                        // Combinar as duas coleções e remover duplicados
                        $allRecommended = $recommendedSpaces->merge($favoriteSpaces)->unique('id');
                    @endphp
                    @foreach($allRecommended as $space)
                        <div class="shrink-0 w-[240px]">
                            @include('spaces.partials.space-card', ['space' => $space])
                        </div>
                    @endforeach
                </div>

                <div id="recommendations-gradient-right" class="absolute right-0 top-0 bottom-0 w-24 bg-gradient-to-l from-gray-100 to-transparent z-10 pointer-events-none transition-opacity duration-300"></div>

                <button id="recommendations-scroll-right" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white rounded-full p-3 shadow-lg hover:bg-gray-50 transition">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/horizontal-scroll.js') }}"></script>
@endpush