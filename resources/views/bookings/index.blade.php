@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold mb-8">My Reservations</h1>
        @if($futureReservations->isEmpty() && $pastReservations->isEmpty() && $cancelledReservations->isEmpty())
            <div class="text-center py-16">
                <p class="text-xl text-gray-600">No reservations found</p>
                <p class="text-sm text-gray-500 mt-2">Customer ID: {{ request()->user_id ?? 'N/A' }}</p>
            </div>
        @else
            @if($futureReservations->isNotEmpty())
                <section>
                    <h2 class="text-2xl font-semibold mb-4">Upcoming Reservations</h2>
                    <div class="flex gap-6 pb-4">
                        @foreach($futureReservations as $booking)
                            @include('bookings.partials.booking-card', ['booking' => $booking])
                        @endforeach
                    </div>
                </section>
            @endif

            @if($pastReservations->isNotEmpty())
                <section>
                    <h2 class="text-2xl font-semibold mb-4">Past Reservations</h2>
                    <div class="flex gap-6 pb-4">
                        @foreach($pastReservations as $booking)
                            @include('bookings.partials.booking-card', ['booking' => $booking])
                        @endforeach
                    </div>
                </section>
            @endif

            @if($cancelledReservations->isNotEmpty())
                <section>
                    <h2 class="text-2xl font-semibold mb-4">Cancelled Reservations</h2>
                    <div class="flex gap-6 pb-4">
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