<a href="{{ route('spaces.bookings', $space->id) }}" class="bg-white rounded-2xl shadow-md overflow-hidden flex flex-col h-full w-64 hover:shadow-lg transition-shadow duration-300">
    <div class="h-40 overflow-hidden bg-gray-200 shrink-0">
        @include('partials.space-image', ['space' => $space])
    </div>
    <div class="p-4">
        <h3 class="font-bold text-lg text-gray-900 mb-2">{{ $space->title }}</h3>
        <p class="flex items-center text-sm text-gray-600 mb-2">
            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            {{ $space->address }}
        </p>
    </div>
</a>