<!-- Payment Modal -->
<div id="paymentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">Confirm Payment</h3>
            <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Payment Methods -->
        <div class="mb-6">
            <p class="font-semibold mb-3">Choose a payment method</p>
            <div class="grid grid-cols-3 gap-2">
                <button onclick="selectPayment('Credit/Debit Card')"
                        class="payment-btn flex flex-col items-center justify-center p-4 border-2 rounded-lg hover:border-blue-500 transition">
                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    <span class="text-xs text-center">Card</span>
                </button>
                <button onclick="selectPayment('MB Way')"
                        class="payment-btn flex flex-col items-center justify-center p-4 border-2 rounded-lg hover:border-blue-500 transition">
                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-xs text-center">MB Way</span>
                </button>
                <button onclick="selectPayment('Paypal')"
                        class="payment-btn flex flex-col items-center justify-center p-4 border-2 rounded-lg hover:border-blue-500 transition">
                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    <span class="text-xs text-center">PayPal</span>
                </button>
            </div>
        </div>

        <!-- Payment Form - Card (mostrado condicionalmente) -->
        <div id="cardPaymentForm" class="hidden mb-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Card Number</label>
                    <input type="text"
                           placeholder="1234 5678 9012 3456"
                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                        <input type="text"
                               placeholder="MM/YY"
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                        <input type="text"
                               placeholder="123"
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Form - MB Way (mostrado condicionalmente) -->
        <div id="mbwayPaymentForm" class="hidden mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <input type="tel"
                       placeholder="+351 912 345 678"
                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>

        <!-- Payment Form - PayPal (mostrado condicionalmente) -->
        <div id="paypalPaymentForm" class="hidden mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email / Phone Number</label>
                <input type="text"
                       placeholder="email@example.com"
                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>

        <!-- Amount Summary -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Amount to pay:</span>
                <span id="paymentAmount" class="text-2xl font-bold text-gray-900">0.00€</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3">
            <button onclick="closePaymentModal()"
                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                Cancel
            </button>
            <button onclick="confirmPayment()"
                    id="confirmPayBtn"
                    class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                Pay Now
            </button>
        </div>
    </div>
</div>

<!-- Payment Success Modal -->
<div id="paymentSuccessModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-sm w-full mx-4 text-center">
        <div class="mb-4">
            <svg class="w-16 h-16 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-2xl font-bold mb-2">Payment Confirmed!</h3>
        <p class="text-gray-600 mb-6">Your booking has been successfully created.</p>
        <button onclick="goToReservations()"
                class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            See Reservations
        </button>
    </div>
</div>
