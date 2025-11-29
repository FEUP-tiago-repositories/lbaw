<!-- Modal de Pagamento -->
<div id="paymentModal" class="hidden fixed inset-0 backdrop-blur-sm z-[9998] backdrop-brightness-50 flex items-center justify-center z-50">
    <div class="text-xl bg-white rounded-lg shadow-xl max-w-md w-full mx-4 z-[9999]">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h2 class="font-bold text-gray-900">Confirm payment</h2>
        </div>

        <!-- Body -->
        <div class="p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Choose a payment method</h3>

            <!-- Métodos de Pagamento -->
            <div class="flex gap-3 mb-6">
                <button type="button" onclick="selectPaymentMethod('card')"
                        class="payment-method flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg hover:border-blue-600 transition"
                        data-method="card">
                    Debit/Credit Card
                </button>
                <button type="button" onclick="selectPaymentMethod('mbway')"
                        class="payment-method flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg hover:border-blue-600 transition"
                        data-method="mbway">
                    MB Way
                </button>
                <button type="button" onclick="selectPaymentMethod('paypal')"
                        class="payment-method flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg hover:border-blue-600 transition"
                        data-method="paypal">
                    PayPal
                </button>
            </div>

            <!-- Formulário de Cartão -->
            <div id="cardForm" class="hidden space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Card number:</label>
                    <input type="text"
                           id="cardNumber"
                           placeholder="0000 0000 0000 0000"
                           maxlength="19"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Expiration Date:</label>
                        <input type="text"
                               id="cardExpiry"
                               placeholder="MM/YY"
                               maxlength="5"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">CVV:</label>
                        <input type="text"
                               id="cardCVV"
                               placeholder="000"
                               maxlength="3"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Formulário MB Way -->
            <div id="mbwayForm" class="hidden space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone number:</label>
                    <div class="flex gap-2">
                        <input type="text"
                               value="+351"
                               readonly
                               class="w-20 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                        <input type="tel"
                               id="mbwayPhone"
                               placeholder="900000000"
                               maxlength="9"
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Formulário PayPal -->
            <div id="paypalForm" class="hidden space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email / Phone number:</label>
                    <input type="text"
                           id="paypalEmail"
                           placeholder="email@example.com"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Total -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">Total:</span>
                    <span id="paymentAmount" class="text-2xl font-bold text-gray-900">€0.00</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-6 border-t border-gray-200 flex gap-3">
            <button type="button"
                    onclick="closePaymentModal()"
                    class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold transition">
                Cancel
            </button>
            <button type="button"
                    onclick="processPayment()"
                    class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition">
                Confirm and pay
            </button>
        </div>
    </div>
</div>
