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
                My Reservations
            </p>
        </div>
        <h1 class="text-3xl font-bold my-8">My Reservations</h1>
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
                            @include('bookings.partials.booking-user-card', ['booking' => $booking])
                        @endforeach
                    </div>
                </section>
            @endif

            @if($pastReservations->isNotEmpty())
                <section>
                    <h2 class="text-2xl font-semibold mb-4">Past Reservations</h2>
                    <div class="flex overflow-x-auto gap-6 pb-4">
                        @foreach($pastReservations as $booking)
                            @include('bookings.partials.booking-user-card', ['booking' => $booking])
                        @endforeach
                    </div>
                </section>
            @endif

            @if($cancelledReservations->isNotEmpty())
                <section>
                    <h2 class="text-2xl font-semibold mb-4">Cancelled Reservations</h2>
                    <div class="flex overflow-x-auto gap-6 pb-4">
                        @foreach($cancelledReservations as $booking)
                            @include('bookings.partials.booking-user-card', ['booking' => $booking])
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