<!-- Modal de Pagamento -->
<div id="paymentModal" class="hidden fixed inset-0 backdrop-blur-sm z-[9998] backdrop-brightness-50 flex items-center justify-center z-50">
    <div class="text-base bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 z-[9999]">
        <!-- Header -->
        <div class="p-4 border-b border-gray-200 bg-gray-600 text-white text-xl font-bold  rounded-t-2xl">
            <h2>Confirm payment</h2>
        </div>

        <!-- Body -->
        <div class="p-6">
            <!-- Payment Details (shown in edit mode) -->
            <div id="paymentDetails" class="hidden mb-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                <!-- Will be populated by JS -->
            </div>

            <h3 class="text-lg font-semibold text-gray-900 mb-4">Choose a payment method</h3>

            <!-- Métodos de Pagamento -->
            <div class="flex gap-3 mb-6">
                <button type="button" onclick="selectPaymentMethod('card')"
                        class="payment-method flex-2 px-4 py-2 border-2 border-gray-300 rounded-xl hover:border-blue-600 transition"
                        data-method="card">
                    Debit/Credit Card
                </button>
                <button type="button" onclick="selectPaymentMethod('mbway')"
                        class="payment-method flex-1 px-4 py-2 border-2 border-gray-300 rounded-xl hover:border-red-600 transition"
                        data-method="mbway">
                    MB Way
                </button>
                <button type="button" onclick="selectPaymentMethod('paypal')"
                        class="payment-method flex-1 px-4 py-2 border-2 border-gray-300 rounded-xl hover:border-blue-600 transition"
                        data-method="paypal">
                    PayPal
                </button>
            </div>

            <!-- Formulário de Cartão -->
            <div id="cardForm" class="hidden space-y-4">
                <div>
                    <label class="block font-medium text-gray-700 mb-2">Card number:</label>
                    <input type="text"
                           id="cardNumber"
                           placeholder="0000 0000 0000 0000"
                           maxlength="19"
                           class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium text-gray-700 mb-2">Expiration Date:</label>
                        <input type="text"
                               id="cardExpiry"
                               placeholder="MM/YY"
                               maxlength="5"
                               class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-2">CVV:</label>
                        <input type="text"
                               id="cardCVV"
                               placeholder="000"
                               maxlength="3"
                               class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Formulário MB Way -->
            <div id="mbwayForm" class="hidden space-y-4">
                <div>
                    <label class="block font-medium text-gray-700 mb-2">Phone number:</label>
                    <div class="flex gap-2">
                        <input type="text"
                               value="+351"
                               readonly
                               class="w-20 px-4 py-2 border border-gray-300 rounded-xl bg-gray-50">
                        <input type="tel"
                               id="mbwayPhone"
                               placeholder="900000000"
                               maxlength="9"
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Formulário PayPal -->
            <div id="paypalForm" class="hidden space-y-4">
                <div>
                    <label class="block font-medium text-gray-700 mb-2">Email / Phone number:</label>
                    <input type="text"
                           id="paypalEmail"
                           placeholder="email@example.com"
                           class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Total -->
            <div class="mt-6 px-4 py-3 bg-gray-50 rounded-xl text-xl">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">Total:</span>
                    <span id="paymentAmount" class="font-bold text-gray-900">€0.00</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-gray-200 flex gap-3">
            <button type="button"
                    onclick="closePaymentModal()"
                    class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-semibold transition flex justify-center items-center gap-1">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Cancel
            </button>
            <button type="button"
                    onclick="processPayment()"
                    class="flex-2 px-4 py-2 bg-emerald-800 text-white rounded-xl hover:bg-emerald-200 hover:text-black font-semibold transition">
                Confirm and pay
            </button>
        </div>
    </div>
</div>

<!-- Modal de Reembolso -->
<div id="refundModal" class="hidden fixed inset-0 backdrop-blur-sm z-[9998] backdrop-brightness-50 flex items-center justify-center z-50">
    <div class="text-base bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 z-[9999]">
        <!-- Header -->
        <div class="p-4 border-b border-gray-200 bg-gray-600 text-white text-xl font-bold  rounded-t-2xl">
            <h2>Booking Update - Refund</h2>
        </div>

        <!-- Body -->
        <div class="p-6">
            <p class="text-gray-600 mb-4">
                The new booking price is lower than the original. You will receive a refund for the difference.
            </p>

            <!-- Price Breakdown -->
            <div class="space-y-3 mb-6">
                <div class="flex justify-between items-center px-4 py-2 bg-gray-50 rounded-2xl">
                    <span class="text-gray-600 font-medium">Original price:</span>
                    <span class="font-semibold text-gray-900">€<span id="refundOldPrice">0.00</span></span>
                </div>

                <div class="flex justify-between items-center px-4 py-2 bg-gray-50 rounded-2xl">
                    <span class="text-gray-600 font-medium">New price:</span>
                    <span class="font-semibold text-gray-900">€<span id="refundNewPrice">0.00</span></span>
                </div>

                <div class="flex justify-between items-center px-4 py-2 bg-green-50 border border-green-200 rounded-2xl">
                    <span class="text-green-700 font-semibold">Refund amount:</span>
                    <span class="font-bold text-green-700 text-lg">€<span id="refundAmount">0.00</span></span>
                </div>
            </div>

            <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                <p class="text-sm text-blue-800">
                    <svg class="w-5 h-5 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    The refund will be processed to your original payment method within 5-7 business days.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-gray-200 flex gap-3">
            <button type="button"
                    onclick="closeRefundModal()"
                    class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-semibold transition">
                Cancel
            </button>
            <button type="button"
                    onclick="confirmRefund()"
                    class="flex-2 px-4 py-2 bg-green-800 text-white rounded-xl hover:bg-green-200 hover:text-black font-semibold transition">
                Confirm update
            </button>
        </div>
    </div>
</div>
