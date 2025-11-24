@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold">My Reservations</h1>
        </div>

        @if($futureReservations->isNotEmpty())
            <section>
                <h2 class="text-2xl font-semibold mb-4">Next Reservations</h2>
                <div class="flex overflow-x-auto gap-6 pb-4">
                    @foreach($futureReservations as $booking)
                        @include('bookings.partials.booking-card', ['booking' => $booking, 'type' => 'future'])
                    @endforeach
                </div>
            </section>
        @endif

        @if($pastReservations->isNotEmpty())
            <section>
                <h2 class="text-2xl font-semibold mb-4">Past Reservations</h2>
                <div class="flex overflow-x-auto gap-6 pb-4">
                    @foreach($pastReservations as $booking)
                        @include('bookings.partials.booking-card', ['booking' => $booking, 'type' => 'past'])
                    @endforeach
                </div>
            </section>
        @endif

        @if($cancelledReservations->isNotEmpty())
            <section>
                <h2 class="text-2xl font-semibold mb-4">Cancelled Reservations</h2>
                <div class="flex overflow-x-auto gap-6 pb-4">
                    @foreach($cancelledReservations as $booking)
                        @include('bookings.partials.booking-card', ['booking' => $booking, 'type' => 'cancelled'])
                    @endforeach
                </div>
            </section>
        @endif

        @if($futureReservations->isEmpty() && $pastReservations->isEmpty() && $cancelledReservations->isEmpty())
            <div class="text-center py-16">
                <p class="text-xl text-gray-600">No reservations found</p>
                <p class="text-sm text-gray-500 mt-2">Customer ID: {{ request()->user_id ?? 'N/A' }}</p>
            </div>
        @endif
    </div>
@endsection
