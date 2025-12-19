<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    {{-- Overall Rating Display --}}
    <div class="flex items-start gap-6">
        {{-- Left side: Rating number, stars, review count --}}
        <div class="flex flex-col items-center">
            <p class="text-5xl font-bold text-gray-900">
                {{ number_format($averageRating, 1) }}
            </p>
            <div class="flex items-center gap-1 mt-2">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                @endfor
            </div>
            <p class="text-sm text-gray-600 mt-1">
                {{ $totalReviews }} {{ Str::plural('review', $totalReviews) }}
            </p>
        </div>

        {{-- Right side: Category ratings with bars --}}
        <div class="flex-1">
            {{-- Environment Rating --}}
            <div class="mb-3">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700">Environment</span>
                    <span class="text-sm font-semibold text-gray-900">{{ number_format($avgEnvironment, 1) }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-emerald-600 h-2.5 rounded-full transition-all duration-300"
                        style="width: {{ ($avgEnvironment / 5) * 100 }}%"></div>
                </div>
            </div>

            {{-- Equipment Rating --}}
            <div class="mb-3">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700">Equipment</span>
                    <span class="text-sm font-semibold text-gray-900">{{ number_format($avgEquipment, 1) }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-emerald-600 h-2.5 rounded-full transition-all duration-300"
                        style="width: {{ ($avgEquipment / 5) * 100 }}%"></div>
                </div>
            </div>

            {{-- Service Rating --}}
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700">Service</span>
                    <span class="text-sm font-semibold text-gray-900">{{ number_format($avgService, 1) }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-emerald-600 h-2.5 rounded-full transition-all duration-300"
                        style="width: {{ ($avgService / 5) * 100 }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>