{{-- filepath: resources/views/spaces/partials/space-card.blade.php --}}
<a href="{{ route('spaces.show', $space->id) }}">
    <div class="bg-white rounded-2xl shadow-md overflow-hidden flex flex-col h-full w-[250px] hover:shadow-lg transition-shadow duration-300">
        <!-- Image -->
        <div class="h-40 overflow-hidden bg-gray-200 shrink-0">
            @include('partials.space-image', ['space' => $space])
        </div>

        <!-- Content -->
        <div class="p-4 flex flex-col grow">
            <h3 class="font-semibold text-lg mb-2">
                {{ $space->title }}
            </h3>

            <!-- Details -->
            <div class="space-y-2 text-sm mb-4 grow">
                <div class="flex items-center text-black">
                    <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
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

                <div class="flex items-center text-gray-700">
                    <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    {{ $space->email }}
                </div>

                <div class="flex items-center text-gray-700">
                    <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    {{ $space->phone_no }}
                </div>

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
                <a href="{{ route('spaces.show', $space->id) }}"
                    class="block w-full bg-emerald-800 text-white text-center px-4 py-2 rounded-xl hover:bg-emerald-200 hover:text-black transition text-sm font-medium">
                    View Details
                </a>
            </div>
        </div>
    </div>
</a>