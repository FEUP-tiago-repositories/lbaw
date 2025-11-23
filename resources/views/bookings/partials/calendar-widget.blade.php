<div class="bg-white rounded-lg shadow-md p-6" data-space-id="{{ $space->id }}">
    <h3 class="text-xl font-bold mb-4">Reserve Space</h3>

    <!-- Step 1: Calendar -->
    <div id="step-calendar">
        <div class="flex justify-between items-center mb-4">
            <button onclick="previousMonth()" class="p-2 hover:bg-gray-100 rounded">
                ←
            </button>
            <span id="currentMonth" class="font-semibold"></span>
            <button onclick="nextMonth()" class="p-2 hover:bg-gray-100 rounded">
                →
            </button>
        </div>

        <div id="calendarGrid" class="grid grid-cols-7 gap-1"></div>
    </div>

    <!-- Step 2: Times -->
    <div id="step-times" class="hidden">
        <button onclick="showStep('step-calendar')" class="text-blue-600 mb-4">← Back</button>
        <h4 class="font-semibold mb-3">Available times:</h4>
        <div id="timesGrid" class="grid grid-cols-4 gap-2"></div>
    </div>

    <!-- Step 3: Duration -->
    <div id="step-duration" class="hidden">
        <button onclick="showStep('step-times')" class="text-blue-600 mb-4">← Back</button>
        <h4 class="font-semibold mb-3">Duration:</h4>
        <div class="flex items-center justify-center gap-4 mb-4">
            <button onclick="changeDuration(-30)" class="bg-gray-200 w-10 h-10 rounded">-</button>
            <span id="durationValue" class="text-xl font-bold">30 min</span>
            <button onclick="changeDuration(30)" class="bg-gray-200 w-10 h-10 rounded">+</button>
        </div>
        <button onclick="showStep('step-persons')" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Continue
        </button>
    </div>

    <!-- Step 4: Persons -->
    <div id="step-persons" class="hidden">
        <button onclick="showStep('step-duration')" class="text-blue-600 mb-4">← Back</button>
        <h4 class="font-semibold mb-3">Number of persons:</h4>
        <div class="flex items-center justify-center gap-4 mb-4">
            <button onclick="changePersons(-1)" class="bg-gray-200 w-10 h-10 rounded">-</button>
            <span id="personsValue" class="text-xl font-bold">1</span>
            <button onclick="changePersons(1)" class="bg-gray-200 w-10 h-10 rounded">+</button>
        </div>
        <button onclick="createBooking()" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
            Confirm and pay
        </button>
    </div>

    <!-- Summary -->
    <div id="bookingSummary" class="hidden mt-6 p-4 bg-gray-50 rounded">
        <div class="text-sm space-y-2">
            <div class="flex justify-between"><span>Date:</span><strong id="summaryDate"></strong></div>
            <div class="flex justify-between"><span>Time:</span><strong id="summaryTime"></strong></div>
            <div class="flex justify-between"><span>Duration:</span><strong id="summaryDuration"></strong></div>
            <div class="flex justify-between"><span>Persons:</span><strong id="summaryPersons"></strong></div>
            <div class="flex justify-between text-lg font-bold border-t pt-2">
                <span>Total:</span><strong id="summaryTotal">0.00€</strong>
            </div>
        </div>
    </div>
</div>
