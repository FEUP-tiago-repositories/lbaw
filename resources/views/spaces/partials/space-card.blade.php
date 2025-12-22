{{-- filepath: resources/views/spaces/partials/space-card.blade.php --}}
<a href="{{ route('spaces.show', $space->id) }}">
    <div
        class="bg-white rounded-2xl shadow-md overflow-hidden flex flex-col h-full w-[240px] hover:shadow-lg transition-shadow duration-300">
        <!-- Image -->
        <div class="h-40 overflow-hidden bg-gray-200 shrink-0">
            @include('partials.space-image', ['space' => $space])
        </div>

        <!-- Content -->
        <div class="p-4 flex flex-col grow">
            <div class="flex items-start justify-between mb-2">
                <h3 class="font-semibold text-lg">
                    {{ $space->title }}
                </h3>
                @php
                    $averageRating = ($space->current_environment_rating +
                        $space->current_equipment_rating +
                        $space->current_service_rating) / 3;
                @endphp

                <div class="flex items-center gap-1 bg-yellow-100 px-2 py-1 rounded-lg">
                    <svg class="w-4 h-4 text-yellow-500 fill-current" viewBox="0 0 24 24">
                        <path
                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                    <span class="text-sm font-bold text-gray-800">{{ number_format($averageRating, 1) }}</span>
                </div>
            </div>

            <!-- Details -->
            <div class="space-y-2 text-sm mb-4 grow">
                <div class="flex items-center text-black">
                    <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ $space->address }}
                </div>

                @if($space->sportType)
                    <div class="flex items-center text-gray-700">
                        <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        {{ $space->sportType->name }}
                    </div>
                @endif

                <div class="text-gray-600 line-clamp-2">
                    {{ $space->description }}
                </div>

                @if($space->is_closed)
                    <div class="mt-2">
                        <span
                            class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-medium hover:bg-red-500 transition-colors duration-300 ease-in-out">
                            Currently Closed
                        </span>
                    </div>
                @endif
            </div>

            <!-- Action Button -->
            <div class="mt-auto">
                <div
                    class="block w-full bg-emerald-800 text-white text-center px-4 py-2 rounded-xl hover:bg-emerald-200 hover:text-black transition text-sm font-medium">
                    View Details
                </div>
            </div>
        </div>
    </div>
</a>