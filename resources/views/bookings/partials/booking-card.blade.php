<div class="bg-white rounded-lg shadow-md overflow-hidden" data-booking-id="{{ $booking->id }}">
    <!-- Image -->
    <div class="h-48 overflow-hidden">
        @if($booking->space->media->isNotEmpty())
            <img src="{{ $booking->space->media->first()->media_url }}"
                 alt="{{ $booking->space->title }}"
                 class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                <span class="text-gray-400">No image</span>
            </div>
        @endif
    </div>

    <!-- Content -->
    <div class="p-4">
        <h3 class="font-semibold text-lg mb-1">{{ $booking->space->title }}</h3>
        <p class="text-gray-600 text-sm mb-4">{{ $booking->space->address }}</p>

        <!-- Details -->
        <div class="space-y-2 text-sm mb-4">
            <div class="flex items-center text-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ $booking->schedule->start_time->format('d/m/Y') }}
            </div>
            <div class="flex items-center text-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $booking->schedule->start_time->format('H:i') }} - {{ $booking->total_duration }}min
            </div>
            <div class="flex items-center font-semibold text-gray-900">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ number_format($booking->payment->value, 2) }}€
            </div>
        </div>

        <!-- Actions -->
        @if($type === 'future')
            <div class="flex gap-2">
                <button onclick="editReservation({{ $booking->id }})"
                        class="flex-1 bg-gray-100 text-gray-700 px-4 py-2 rounded hover:bg-gray-200">
                    Edit
                </button>
                <button onclick="openCancelModal({{ $booking->id }}, '{{ $booking->space->title }}', '{{ $booking->schedule->start_time->format('d/m/Y') }}', '{{ $booking->schedule->start_time->format('H:i') }}', {{ $booking->payment->value }})"
                        class="flex-1 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    Cancel
                </button>
            </div>
        @endif

        @if($booking->is_cancelled)
            <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">
            Cancelled
        </span>
        @endif
    </div>
</div>
