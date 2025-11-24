@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="mb-8">
            <a href="{{ route('test.bookings', $booking->customer->user_id) }}"
               class="text-blue-600 hover:underline mb-4 inline-block">
                ← Back to my reservations
            </a>
            <h1 class="text-3xl font-bold">Edit Reservation</h1>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <!-- Current Booking Info -->
            <div class="mb-6 pb-6 border-b">
                <h2 class="text-xl font-semibold mb-4">Current Booking</h2>
                <div class="space-y-2 text-sm">
                    <p><strong>Space:</strong> {{ $booking->space->title }}</p>
                    <p><strong>Date:</strong> {{ $booking->schedule->start_time->format('d/m/Y') }}</p>
                    <p><strong>Time:</strong> {{ $booking->schedule->start_time->format('H:i') }}</p>
                    <p><strong>Duration:</strong> {{ $booking->total_duration }} minutes</p>
                    <p><strong>Persons:</strong> {{ $booking->number_of_persons }}</p>
                    <p><strong>Total:</strong> {{ number_format($booking->payment->value, 2) }}€</p>
                </div>
            </div>

            <!-- Edit Form (Simulado) -->
            <form id="editBookingForm" class="space-y-6">
                @csrf

                <!-- Date Picker -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        New Date
                    </label>
                    <input type="date"
                           id="newDate"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                           value="{{ $booking->schedule->start_time->format('Y-m-d') }}">
                </div>

                <!-- Time Selector (será populado via JS) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Available Times
                    </label>
                    <div id="timeSlots" class="grid grid-cols-4 gap-2">
                        <button type="button" class="time-slot-btn p-2 border rounded hover:bg-blue-50">
                            {{ $booking->schedule->start_time->format('H:i') }}
                        </button>
                        <!-- Mais slots serão adicionados via JS -->
                    </div>
                </div>

                <!-- Duration -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Duration (minutes)
                    </label>
                    <div class="flex items-center gap-4">
                        <button type="button"
                                onclick="changeDuration(-30)"
                                class="bg-gray-200 w-10 h-10 rounded hover:bg-gray-300">
                            -
                        </button>
                        <span id="durationValue" class="text-xl font-bold">
                        {{ $booking->total_duration }}
                    </span>
                        <button type="button"
                                onclick="changeDuration(30)"
                                class="bg-gray-200 w-10 h-10 rounded hover:bg-gray-300">
                            +
                        </button>
                    </div>
                </div>

                <!-- Number of Persons -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Number of Persons
                    </label>
                    <div class="flex items-center gap-4">
                        <button type="button"
                                onclick="changePersons(-1)"
                                class="bg-gray-200 w-10 h-10 rounded hover:bg-gray-300">
                            -
                        </button>
                        <span id="personsValue" class="text-xl font-bold">
                        {{ $booking->number_of_persons }}
                    </span>
                        <button type="button"
                                onclick="changePersons(1)"
                                class="bg-gray-200 w-10 h-10 rounded hover:bg-gray-300">
                            +
                        </button>
                    </div>
                </div>

                <!-- New Total -->
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="flex justify-between items-center text-lg font-semibold">
                        <span>New Total:</span>
                        <span id="newTotal">{{ number_format($booking->payment->value, 2) }}€</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-2" id="priceDifferenceMsg">
                        <!-- Mensagem de diferença de preço -->
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <a href="{{ route('test.bookings', $booking->customer->user_id) }}"
                       class="flex-1 text-center bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </a>
                    <button type="button"
                            onclick="saveChanges()"
                            class="flex-1 bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    @include('bookings.partials.payment-modal')

    <script>
        let currentDuration = {{ $booking->total_duration }};
        let currentPersons = {{ $booking->number_of_persons }};
        const originalValue = {{ $booking->payment->value }};
        const bookingId = {{ $booking->id }};

        function changeDuration(amount) {
            currentDuration = Math.max(30, currentDuration + amount);
            document.getElementById('durationValue').textContent = currentDuration;
            updateTotal();
        }

        function changePersons(amount) {
            currentPersons = Math.max(1, currentPersons + amount);
            document.getElementById('personsValue').textContent = currentPersons;
            updateTotal();
        }

        function updateTotal() {
            const numSchedules = Math.ceil(currentDuration / 30);
            const newTotal = numSchedules * currentPersons * 10;

            document.getElementById('newTotal').textContent = newTotal.toFixed(2) + '€';

            const difference = newTotal - originalValue;
            const msgElement = document.getElementById('priceDifferenceMsg');

            if (difference > 0) {
                msgElement.textContent = `Additional payment required: ${difference.toFixed(2)}€`;
                msgElement.className = 'text-sm text-orange-600 mt-2';
            } else if (difference < 0) {
                msgElement.textContent = `Refund: ${Math.abs(difference).toFixed(2)}€`;
                msgElement.className = 'text-sm text-green-600 mt-2';
            } else {
                msgElement.textContent = 'No price change';
                msgElement.className = 'text-sm text-gray-600 mt-2';
            }
        }

        function saveChanges() {
            alert('Edit functionality will save to API:\n' +
                'Booking ID: ' + bookingId + '\n' +
                'New Duration: ' + currentDuration + ' min\n' +
                'New Persons: ' + currentPersons + '\n' +
                'Coming soon!');
        }
    </script>
@endsection
