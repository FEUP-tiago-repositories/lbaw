@php
    use Carbon\Carbon;
@endphp

<div class="bg-white rounded-2xl shadow-md overflow-hidden flex flex-col flex-none w-64">
    <!-- Image -->
    <div class="h-48 overflow-hidden bg-gray-200 shrink-0">
        @include('partials.space-image', ['space' => $booking->space])
    </div>

    <!-- Content - Flex grow to push buttons to bottom -->
    <div class="p-4 flex flex-col flex-grow">
        <h3 class="font-semibold text-lg mb-3 line-clamp-2">{{ $booking->space->title ?? 'Space #' . $booking->space_id }}</h3>

        <!-- Details -->
        <div class="space-y-2 text-sm mb-auto">
            <div class="flex items-start text-gray-700">
                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="line-clamp-2">{{ $booking->space->address ?? 'No address' }}</span>
            </div>
            <div class="flex items-center text-gray-700">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ Carbon::parse($booking->schedule->start_time)->format('l, d/m/Y') }}
            </div>
            <div class="flex items-center text-gray-700">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ $booking->schedule->start_time->format('H:i') }} - {{ $booking->total_duration }} min
            </div>
            <div class="flex items-center text-gray-700">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                {{ $booking->number_of_persons }} {{ $booking->number_of_persons > 1 ? 'persons' : 'person' }}
            </div>
            <div class="flex items-center text-gray-700">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                @if($booking->payment)
                    {{ number_format($booking->payment->value, 2) }}€
                @else
                    N/A
                @endif
            </div>
        </div>

        <!-- Action Buttons - Fixed at bottom -->
        <div class="mt-1 pt-2 border-t border-gray-200">
            @if($booking->is_cancelled)
                <div class="flex flex-col gap-2">
                    <div class="flex items-center justify-center bg-red-100 text-red-800 px-2 py-1.5 rounded-xl text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancelled
                    </div>
                    <button onclick="window.location.href='{{ route('spaces.show', $booking->space_id) }}'"
                            class="flex items-center justify-center bg-emerald-600 text-white px-2 py-1.5 rounded-xl hover:bg-emerald-700 transition text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Repeat
                    </button>
                </div>
            @elseif($booking->isFuture())
                <!-- Buttons for future reservations -->
                <div class="flex flex-col gap-2">
                    <button onclick="window.location.href='{{ route('bookings.edit', $booking->id) }}'"
                            class="flex items-center justify-center border-2 border-gray-300 bg-white text-gray-700 px-2 py-1.5 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit reservation
                    </button>
                    <button type="button"
                            data-booking-id="{{ $booking->id }}"
                            data-space-name="{{ $booking->space->title }}"
                            data-date="{{ $booking->schedule->start_time->format('d/m/Y') }}"
                            data-time="{{ $booking->schedule->start_time->format('H:i') }}"
                            data-duration="{{ $booking->total_duration }}"
                            data-amount="{{ $booking->payment->value }}"
                            data-space-id="{{ $booking->space_id }}"
                            data-schedule-id="{{ $booking->schedule_id }}"
                            onclick="openCancelModalFromData(this)"
                            class="flex items-center justify-center bg-red-600 text-white px-2 py-1.5 rounded-xl hover:bg-red-700 transition text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Cancel reservation
                    </button>
                </div>
            @elseif($booking->isPast())
                <!-- Buttons for past reservations -->
                <div class="flex flex-col gap-2">
                    <a href="{{ route('spaces.show', $booking->space_id) }}"
                       class="flex items-center justify-center border-2 border-gray-300 bg-white text-gray-700 px-2 py-1.5 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        Write a review
                    </a>
                    <button onclick="window.location.href='{{ route('spaces.show', $booking->space_id) }}'"
                            class="flex items-center justify-center bg-emerald-600 text-white px-2 py-1.5 rounded-xl hover:bg-emerald-700 transition text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Repeat
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
