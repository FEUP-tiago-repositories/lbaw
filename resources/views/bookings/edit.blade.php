@extends('layouts.app')

@section('content')
    <div class="min-h-screen py-8">
        <div>
            @include('bookings.partials.calendar-widget', ['booking' => $booking])
        </div>
    </div>

    <!-- Modais -->
    @include('bookings.modals.payment-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/booking.js') }}"></script>
@endpush
