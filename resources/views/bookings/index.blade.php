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
                Perfil de {{ Auth::user()->user_name }}
            </a>
            <svg class="w-5 h-5 pt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <p>
                My Reservations
            </p>
        </div>
        <div class = "flex justify-between items-center mb-6">
            <h1 class="text-4xl font-bold mb-8">My Reservations</h1>

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
                <p class="text-sm text-gray-500 mt-2">Customer ID: {{ request()->user_id ?? 'N/A' }}</p>
            </div>
        @else
            @if($futureReservations->isNotEmpty())
                <section>
                    <h2 class="text-2xl font-semibold mb-4">Upcoming Reservations</h2>
                    <div class="flex overflow-x-auto gap-6 pb-4">
                        @foreach($futureReservations as $booking)
                            @include('bookings.partials.booking-card', ['booking' => $booking])
                        @endforeach
                    </div>
                </section>
            @endif

            @if($pastReservations->isNotEmpty())
                <section>
                    <h2 class="text-2xl font-semibold mb-4">Past Reservations</h2>
                    <div class="flex overflow-x-auto gap-6 pb-4">
                        @foreach($pastReservations as $booking)
                            @include('bookings.partials.booking-card', ['booking' => $booking])
                        @endforeach
                    </div>
                </section>
            @endif

            @if($cancelledReservations->isNotEmpty())
                <section>
                    <h2 class="text-2xl font-semibold mb-4">Cancelled Reservations</h2>
                    <div class="flex overflow-x-auto gap-6 pb-4">
                        @foreach($cancelledReservations as $booking)
                            @include('bookings.partials.booking-card', ['booking' => $booking])
                        @endforeach
                    </div>
                </section>
            @endif
        @endif
    </div>
    @include('bookings.modals.cancel-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/booking.js') }}"></script>
@endpush