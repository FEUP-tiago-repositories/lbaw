<div class="bg-white rounded-lg shadow-md overflow-hidden flex-none w-90">
<!-- Image -->
    <div class="h-48 overflow-hidden bg-gray-200">
        @if($booking->space && $booking->space->media->isNotEmpty())
            <img src="{{ $booking->space->media->first()->media_url }}"
                 alt="{{ $booking->space->title }}"
                 class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        @endif
    </div>

    <!-- Content -->
    <div class="p-4">
        <h3 class="font-semibold text-lg mb-2">
            {{ $booking->space->title ?? 'Space #' . $booking->space_id }}
        </h3>

        <!-- Details -->
        <div class="space-y-2 text-sm mb-4">
            <div class="flex items-center text-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ $booking->space->address ?? 'No address' }}
            </div>
            <div class="flex items-center text-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ \Carbon\Carbon::parse($booking->schedule->start_time)->format('l, d/m/Y') }}
            </div>
            <div class="flex items-center text-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $booking->schedule->start_time->format('H:i') }} - {{ $booking->total_duration }}min
            </div>
            <div class="flex items-center text-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                {{ $booking->number_of_persons }} {{ $booking->number_of_persons > 1 ? 'persons' : 'person' }}
            </div>
            <div class="flex items-center text-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ number_format($booking->payment->value, 2) }}€
            </div>
        </div>

        <!-- Action Buttons -->
        @if($booking->is_cancelled)
            <div class="mt-4">
                <span class="inline-block bg-red-100 text-red-800 px-4 py-2 rounded-lg text-sm font-medium">
                    ✗ Cancelled
                </span>
            </div>
        @elseif($type === 'future')
            <!-- Botões para reservas futuras -->
            <div class="flex gap-2 mt-4">
                <button onclick="openEditModal({{ $booking->id }})"
                        class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                    Edit reservation
                </button>
                <button onclick="openCancelModal({{ $booking->id }}, '{{ addslashes($booking->space->title ?? 'Space') }}', '{{ $booking->schedule->start_time->format('d/m/Y') }}', '{{ $booking->schedule->start_time->format('H:i') }}', {{ $booking->payment->value }})"
                        class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition text-sm font-medium">
                    Cancel reservation
                </button>
            </div>
        @elseif($type === 'past')
            <!-- Botões para reservas passadas -->
            <div class="flex gap-2 mt-4">
                <button onclick="alert('Write review feature - Coming soon!')"
                        class="flex-1 bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition text-sm font-medium">
                    Write a review
                </button>
                <button onclick="alert('Repeat booking feature - Coming soon!')"
                        class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                    Repeat reservation
                </button>
            </div>
        @endif
    </div>
</div>
