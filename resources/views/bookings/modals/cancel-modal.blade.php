<!-- Cancel Modal -->
<div id="cancelModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold mb-4">Cancel Reservation?</h3>

        <div class="mb-6 space-y-2 text-sm">
            <p><strong>Space:</strong> <span id="cancelSpace"></span></p>
            <p><strong>Date:</strong> <span id="cancelDate"></span></p>
            <p><strong>Time:</strong> <span id="cancelTime"></span></p>
            <p><strong>Refund:</strong> <span id="cancelRefund"></span>€</p>
        </div>

        <p class="text-gray-600 mb-6">Your payment will be refunded.</p>

        <div class="flex gap-3">
            <button onclick="closeCancelModal()"
                    class="flex-1 bg-gray-200 text-gray-800 py-2 rounded hover:bg-gray-300">
                Keep
            </button>
            <button onclick="confirmCancel()"
                    class="flex-1 bg-red-600 text-white py-2 rounded hover:bg-red-700">
                Cancel Booking
            </button>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold mb-4">Confirm Payment</h3>

        <div class="mb-4">
            <p class="font-semibold mb-2">Payment Method:</p>
            <div class="grid grid-cols-3 gap-2">
                <button onclick="selectPayment('Credit/Debit Card')"
                        class="payment-btn p-3 border rounded hover:bg-blue-50">
                    Card
                </button>
                <button onclick="selectPayment('MB Way')"
                        class="payment-btn p-3 border rounded hover:bg-blue-50">
                    MB Way
                </button>
                <button onclick="selectPayment('Paypal')"
                        class="payment-btn p-3 border rounded hover:bg-blue-50">
                    PayPal
                </button>
            </div>
        </div>

        <div class="mb-6 p-4 bg-gray-50 rounded">
            <div class="flex justify-between text-lg font-bold">
                <span>Total:</span>
                <span id="paymentAmount">0.00€</span>
            </div>
        </div>

        <div class="flex gap-3">
            <button onclick="closePaymentModal()"
                    class="flex-1 bg-gray-200 py-2 rounded">
                Cancel
            </button>
            <button onclick="confirmPayment()"
                    class="flex-1 bg-green-600 text-white py-2 rounded hover:bg-green-700">
                Pay Now
            </button>
        </div>
    </div>
</div>
