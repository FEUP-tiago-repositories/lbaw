@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-2xl mx-auto px-4">
            @include('bookings.partials.calendar-widget', ['booking' => $booking])
        </div>
    </div>

    <!-- Modais -->
    @include('bookings.modals.payment-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/booking.js') }}"></script>
@endpush
