@php
    // Determinar modo: 'create' (space page) ou 'edit' (edit page)
    $mode = isset($booking) ? 'edit' : 'create';
    $spaceId = $mode === 'edit' ? $booking->space_id : ($space->id ?? '');
@endphp

<div class="booking-widget bg-white rounded-2xl shadow-lg border border-gray-200 max-w-105 mx-auto"
     data-space-id="{{ $spaceId }}"
     data-mode="{{ $mode }}">

    <!-- Header -->
    <div class="p-4 border-b-2 border-white bg-gray-600 text-white rounded-t-2xl text-center">
        <h2 class="text-2xl font-bold">
            {{ $mode === 'edit' ? 'Edit Reservation' : 'Book Space' }}
        </h2>
    </div>

    <!-- Calendar Section (Sempre visível) -->
    <div class="p-4" id="calendar-section">
        <!-- Month Navigation -->
        <div class="flex items-center justify-between mb-4">
            <button type="button" id="prevMonth" class="p-2 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <h3 id="currentMonth" class="text-xl font-bold text-black"></h3>
            <button type="button" id="nextMonth" class="p-2 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        <!-- Calendar Grid -->
        <div id="calendarGrid" class="text-[15px]/7 grid grid-cols-7 gap-1 text-center"></div>
    </div>

    <!-- Time Selection (Mostra após selecionar data) -->
    <div id="time-section" class="hidden p-4 border-t border-gray-200">
        <h3 class="text-lg font-semibold text-black mb-3">Available times:</h3>
        <div id="timeGrid" class="text-[15px]/7 grid grid-cols-[repeat(auto-fit,minmax(60px,1fr))] gap-1 text-center"></div>
    </div>

    <!-- Duration Section (Mostra após selecionar hora) -->
    <div id="duration-section" class="hidden p-4 text-lg border-t border-gray-200">
        <h3 class="font-semibold text-black mb-3">Duration:</h3>
        <div class="flex items-center justify-center gap-3">
            <button type="button" onclick="decrementDuration()" class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-red-200 rounded-xl transition">
                <span class="font-bold">-</span>
            </button>
            <input type="number"
                   id="durationInput"
                   value="30"
                   min="15"
                   step="15"
                   onchange="updateDuration()"
                   class="w-20 text-center border border-gray-200 rounded-xl pl-3 py-1 font-semibold focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <span class="text-base text-gray-600">min.</span>
            <button type="button" onclick="incrementDuration()" class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-blue-200 rounded-xl transition">
                <span class="font-bold">+</span>
            </button>
        </div>
    </div>

    <!-- Number of Persons Section (Mostra após definir duração) -->
    <div id="persons-section" class="hidden p-4 border-t text-lg font-bold border-gray-200">
        <h3 class="font-semibold mb-3">Number of persons:</h3>
        <div class="flex items-center justify-center gap-3">
            <button type="button" onclick="decrementPersons()" class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-red-200 rounded-xl transition">
                <span>-</span>
            </button>
            <input type="number"
                   id="personsInput"
                   value="1"
                   min="1"
                   onchange="updatePersons()"
                   class="w-20 text-center border border-gray-200 rounded-xl pl-3 py-1 font-semibold focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            <button type="button" onclick="incrementPersons()" class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-blue-200 rounded-xl transition">
                <span>+</span>
            </button>
        </div>
    </div>

    <!-- Confirm Button (Mostra após preencher tudo) -->
    <div id="confirm-section" class="hidden p-4 border-t border-gray-200">
        <button type="button"
                onclick="createBooking()"
                class="text-lg w-full bg-emerald-800 hover:bg-emerald-200 text-white hover:text-black font-semibold py-2 rounded-2xl transition shadow-sm">
            {{ $mode === 'edit' ? 'Update and pay' : 'Confirm and pay' }}
        </button>
    </div>

    <!-- Loading State -->
    <div id="loading-state" class="hidden p-4 text-center">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-gray-200 border-t-emerald-600"></div>
        <p class="text-md mt-2">Loading...</p>
    </div>
</div>

@if($mode === 'edit')
    <script>
        // Dados para preencher em modo de edição
        window.editMode = true;
        window.bookingData = {
            id: {{ $booking->id }},
            spaceId: {{ $booking->space_id }},
            scheduleId: {{ $booking->schedule_id }},
            date: '{{ $booking->schedule->start_time->format('Y-m-d') }}',
            time: '{{ $booking->schedule->start_time->format('H:i') }}',
            duration: {{ $booking->total_duration }},
            persons: {{ $booking->number_of_persons }}
        };
    </script>
@endif
