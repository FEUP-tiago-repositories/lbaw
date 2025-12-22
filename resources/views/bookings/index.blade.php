@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <div class="flex items-center justify-start gap-2 mb-4 text-lg">
            <a href="{{ route('home') }}" class="text-emerald-600 hover:text-emerald-400">
                <img alt="Home Page" src="/images/home-icon.svg" height="18" width="18">
            </a>
            <svg class="w-5 h-5 pt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('users.show', Auth::id()) }}" class="text-emerald-600 hover:text-emerald-400">
                Profile of {{ Auth::user()->first_name }} {{ Auth::user()->surname }}
            </a>
            <svg class="w-5 h-5 pt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <p>
                My Reservations
            </p>
        </div>
        <div class = "flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">My Reservations</h1>

            <button type="button" onclick="toggleModal()"
                class="w-12 h-12 rounded-full bg-emerald-700 text-white font-bold flex items-center justify-center hover:bg-emerald-500 transition shadow-lg">
                ?
            </button>
        </div>

        <div id="helpModal" class="fixed inset-0 bg-transparent bg-opacity-60 flex items-center justify-center z-50 hidden backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-3xl w-full min-h-[500px] relative transform transition-all scale-100 mx-4">
                <div class="text-gray-600 text-center mb-8 leading-relaxed">
                    @include('partials.help.manage_reservation')
                </div>

                <div class="flex justify-center">
                    <button onclick="toggleModal()" class="px-8 py-3 bg-red-600 text-white rounded-full font-semibold shadow-lg hover:bg-red-700 hover:shadow-xl transition transform hover:-translate-y-0.5">
                        Close
                    </button>
                </div>
            </div>
        </div>

        @if($futureReservations->isEmpty() && $pastReservations->isEmpty() && $cancelledReservations->isEmpty())
            <div class="text-center py-16">
                <p class="text-xl text-gray-600">No reservations found</p>
                <a href="{{ route('spaces.index') }}" class="inline-block bg-emerald-600 text-white px-6 py-2 mt-2 rounded-lg hover:bg-emerald-700 transition">
                    Explore Spaces
                </a>
            </div>
        @else
            @if($futureReservations->isNotEmpty())
                <section class="mb-4">
                    <h2 class="text-2xl font-semibold mb-4">Upcoming Reservations</h2>
                    <div class="relative">
                        <!-- Gradiente Esquerdo -->
                        <div id="future-gradient-left" class="absolute left-0 top-0 bottom-0 w-24 bg-gradient-to-r from-gray-100 to-transparent z-10 pointer-events-none opacity-0 transition-opacity duration-300"></div>

                        <!-- Seta Esquerda -->
                        <button id="future-scroll-left" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white rounded-full p-3 shadow-lg hover:bg-gray-50 transition opacity-0 pointer-events-none">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <!-- Container com Scroll Horizontal -->
                        <div id="future-scroll-container" class="flex overflow-x-auto gap-4 pb-4 scroll-smooth scrollbar-hide"
                             style="-ms-overflow-style: none; scrollbar-width: none;">
                            @foreach($futureReservations as $booking)
                                @include('bookings.partials.booking-user-card', ['booking' => $booking])
                            @endforeach
                        </div>

                        <!-- Gradiente Direito -->
                        <div id="future-gradient-right" class="absolute right-0 top-0 bottom-0 w-24 bg-gradient-to-l from-gray-100 to-transparent z-10 pointer-events-none transition-opacity duration-300"></div>

                        <!-- Seta Direita -->
                        <button id="future-scroll-right" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white rounded-full p-3 shadow-lg hover:bg-gray-50 transition">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </section>
            @endif

        @if($pastReservations->isNotEmpty())
                <section>
                    <h2 class="text-2xl font-semibold mb-4">Past Reservations</h2>
                    <div class="relative">
                        <!-- Gradiente Esquerdo -->
                        <div id="past-gradient-left" class="absolute left-0 top-0 bottom-0 w-24 bg-gradient-to-r from-gray-100 to-transparent z-10 pointer-events-none opacity-0 transition-opacity duration-300"></div>

                        <!-- Seta Esquerda -->
                        <button id="past-scroll-left" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white rounded-full p-3 shadow-lg hover:bg-gray-50 transition opacity-0 pointer-events-none">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <!-- Container com Scroll Horizontal -->
                        <div id="past-scroll-container" class="flex overflow-x-auto gap-4 pb-4 scroll-smooth scrollbar-hide"
                             style="-ms-overflow-style: none; scrollbar-width: none;">
                            @foreach($pastReservations as $booking)
                                @include('bookings.partials.booking-user-card', ['booking' => $booking])
                            @endforeach
                        </div>

                        <!-- Gradiente Direito -->
                        <div id="past-gradient-right" class="absolute right-0 top-0 bottom-0 w-24 bg-gradient-to-l from-gray-100 to-transparent z-10 pointer-events-none transition-opacity duration-300"></div>

                        <!-- Seta Direita -->
                        <button id="past-scroll-right" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white rounded-full p-3 shadow-lg hover:bg-gray-50 transition">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </section>
            @endif

            @if($cancelledReservations->isNotEmpty())
                <section>
                    <h2 class="text-2xl font-semibold mb-4">Cancelled Reservations</h2>
                    <div class="relative">
                        <!-- Gradiente Esquerdo -->
                        <div id="cancelled-gradient-left" class="absolute left-0 top-0 bottom-0 w-24 bg-gradient-to-r from-gray-100 to-transparent z-10 pointer-events-none opacity-0 transition-opacity duration-300"></div>

                        <!-- Seta Esquerda -->
                        <button id="cancelled-scroll-left" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white rounded-full p-3 shadow-lg hover:bg-gray-50 transition opacity-0 pointer-events-none">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <!-- Container com Scroll Horizontal -->
                        <div id="cancelled-scroll-container" class="flex overflow-x-auto gap-4 pb-4 scroll-smooth scrollbar-hide"
                             style="-ms-overflow-style: none; scrollbar-width: none;">
                            @foreach($cancelledReservations as $booking)
                                @include('bookings.partials.booking-user-card', ['booking' => $booking])
                            @endforeach
                        </div>

                        <!-- Gradiente Direito -->
                        <div id="cancelled-gradient-right" class="absolute right-0 top-0 bottom-0 w-24 bg-gradient-to-l from-gray-100 to-transparent z-10 pointer-events-none transition-opacity duration-300"></div>

                        <!-- Seta Direita -->
                        <button id="cancelled-scroll-right" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white rounded-full p-3 shadow-lg hover:bg-gray-50 transition">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </section>
            @endif
        @endif
    </div>
    @include('bookings.modals.cancel-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/booking.js') }}"></script>
    <script src="{{ asset('js/horizontal-scroll.js') }}"></script>
@endpush