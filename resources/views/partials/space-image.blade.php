@props(['space', 'imageClass' => 'w-full h-full object-cover'])

@if($space->media->isNotEmpty())
    <img src="{{ $space->media->first()->media_url }}"
         alt="{{ $space->title }}"
         class="w-full h-full object-cover"
         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
    <div class="w-full h-full hidden items-center justify-center">
        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
    </div>
@else
    <div class="w-full h-full flex items-center justify-center">
        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
    </div>
@endif