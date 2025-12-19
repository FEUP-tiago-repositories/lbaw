<!-- Modal de Cancelamento -->
<div id="cancelModal" class="hidden fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 p-6">
        <h2 class="text-xl font-bold text-center">Are you sure you want to cancel this reservation?</h2>
        <p class="text-base mb-4 text-center">This action cannot be undone.</p>

        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Space:</span>
                    <span id="cancelSpaceName" class="font-semibold"></span>
                </div>
                @if(auth()->user()->businessOwner)
                    <div class="flex justify-between">
                        <div class="flex items-center text-gray-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="text-gray-600">Customer</span>
                        </div>
                        <span id="cancelCustomerName" class="font-semibold"></span>
                    </div>
                @endif
                <div class="flex justify-between">
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-gray-600">Reservation's Date:</span>
                    </div>
                    <span id="cancelDate" class="font-semibold"></span>
                </div>
                <div class="flex justify-between">
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-gray-600">Start Time and Duration:</span>
                    </div>
                    <span id="cancelTime" class="font-semibold"></span>
                </div>
                <div class="flex justify-between border-t pt-2 mt-2">
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-gray-600">Refund Amount:</span>
                    </div>
                    <span id="cancelAmount" class="font-semibold text-green-600">€0.00</span>
                </div>
            </div>
        </div>

        <input type="hidden" id="cancelBookingId" value="">
        <input type="hidden" id="cancelSpaceId" value="">
        <input type="hidden" id="cancelScheduleId" value="">

        <div class="flex gap-3">
            <button
                    type="button"
                    onclick="closeCancelModal()"
                    class="flex-1 px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition text-sm font-medium">
                Keep Reservation
            </button>
            <button
                    type="button"
                    onclick="confirmCancel()"
                    class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition text-sm font-medium">
                Confirm Cancellation
            </button>
        </div>
    </div>
</div>


