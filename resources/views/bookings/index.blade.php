@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('profile', Auth::id()) }}" class="text-blue-600 hover:underline mb-4 inline-block">
                ← Back to my profile
            </a>
            <h1 class="text-3xl font-bold">My Reservations</h1>
        </div>

        <!-- Future Reservations -->
        @if($futureReservations->isNotEmpty())
            <section class="mb-12">
                <h2 class="text-2xl font-semibold mb-4">Next Reservations</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($futureReservations as $booking)
                        @include('bookings.partials.booking-card', ['booking' => $booking, 'type' => 'future'])
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Past Reservations -->
        @if($pastReservations->isNotEmpty())
            <section>
                <h2 class="text-2xl font-semibold mb-4">Past Reservations</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($pastReservations as $booking)
                        @include('bookings.partials.booking-card', ['booking' => $booking, 'type' => 'past'])
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Empty State -->
        @if($futureReservations->isEmpty() && $pastReservations->isEmpty())
            <div class="text-center py-16">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No reservations yet</h3>
                <p class="text-gray-500 mb-6">Start exploring sports spaces!</p>
                <a href="{{ route('spaces.index') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Browse Spaces
                </a>
            </div>
        @endif
    </div>

    @include('bookings.partials.cancel-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/booking.js') }}"></script>
@endpush
