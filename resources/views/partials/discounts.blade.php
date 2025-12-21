<div id="discountsListModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 overflow-hidden transform transition-all">
        
        <div class="bg-emerald-600 p-4 flex justify-between items-center">
            <h3 class="text-white text-lg font-bold flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                </svg>
                Special Discounts
            </h3>
            <button onclick="closeDiscountModal()" class="text-white hover:bg-emerald-700 rounded-full p-1 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <div class="p-6 bg-gray-50 max-h-[60vh] overflow-y-auto">
            @if($space->discounts && $space->discounts->count() > 0)
                <div class="space-y-3">
                    @foreach($space->discounts as $discount)
                        @php
                            $isActive = \Carbon\Carbon::parse($discount->end_date)->endOfDay()->isFuture() && 
                                        \Carbon\Carbon::parse($discount->start_date)->startOfDay()->isPast();
                        @endphp

                        @if($isActive)
                            <div class="bg-white border-2 border-dashed border-emerald-300 rounded-lg p-4 relative group hover:border-emerald-500 transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="text-xs font-bold text-emerald-600 uppercase tracking-wide"></span>
                                        <div class="text-2xl font-mono font-bold text-gray-800 select-all cursor-pointer" onclick="copyToClipboard('{{ $discount->code }}', this)">
                                            {{ $discount->code }}
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Valid until {{ \Carbon\Carbon::parse($discount->end_date)->format('d/m/Y') }}
                                        </p>
                                    </div>
                                    <div class="bg-emerald-100 text-emerald-800 font-bold px-3 py-1 rounded-full text-sm">
                                        -{{ $discount->percentage }}%
                                    </div>
                                </div>
                                <div class="hidden group-hover:block absolute top-2 right-2 text-xs text-gray-400">
                                    Click to copy
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <p class="text-center text-xs text-gray-400 mt-4">Copy the code and use it at checkout.</p>
            @else
                <p class="text-center text-gray-500">There are no active discounts at the moment.</p>
            @endif
        </div>

        <div class="p-4 border-t border-gray-200 bg-white">
            <button onclick="closeDiscountModal()" class="w-full py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg font-semibold transition">
                Close
            </button>
        </div>
    </div>