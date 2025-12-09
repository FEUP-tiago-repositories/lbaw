<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-4">
    {{-- Review Header --}}
    <div class="flex items-start justify-between mb-4">
        {{-- Left: User info and overall rating --}}
        <div class="flex items-start gap-4">
            {{-- Profile Picture --}}
            <div class="shrink-0 w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                @if($review->customer->user->profile_pic_url)
                    <img src="{{ $review->customer->user->profile_pic_url }}" alt=""
                        class="w-full h-full object-cover"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <svg class="w-8 h-8 text-gray-400 hidden" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd" />
                    </svg>
                @else
                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd" />
                    </svg>
                @endif
            </div>

            {{-- User name and overall rating --}}
            <div>
                <p class="font-semibold text-gray-900 text-lg">{{ $review->customer->user->user_name}}</p>
                <div class="flex items-center gap-2 mt-1">
                    @php
                        $overallRating = ($review->environment_rating + $review->equipment_rating + $review->service_rating) / 3;
                    @endphp
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= round($overallRating) ? 'text-yellow-400' : 'text-gray-300' }}"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                    <span class="text-sm font-semibold text-gray-700 ml-1">{{ number_format($overallRating, 1) }}</span>
                </div>
            </div>
        </div>

        {{-- Right: Date --}}
        <p class="text-sm text-gray-500">{{ $review->time_stamp->format('F j, Y') }}</p>
    </div>

    {{-- Category Ratings (Env, Equipment and Service) --}}
    <div class="flex items-center justify-around mb-4 py-4 bg-gray-200 rounded-lg shadow-sm">
        {{-- Environment rating --}}
        <div class="text-center">
            <p class="text-sm text-gray-600 mb-2">Environment</p>
            <div class="flex items-center justify-center gap-1">
                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="text-sm font-semibold text-gray-900">{{ $review->environment_rating }}</span>
            </div>
        </div>

        {{-- Divider --}}
        <div class="h-12 border-l border-gray-300"></div>

        {{-- Equipment rating --}}
        <div class="text-center">
            <p class="text-sm text-gray-600 mb-2">Equipment</p>
            <div class="flex items-center justify-center gap-1">
                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="text-sm font-semibold text-gray-900">{{ $review->equipment_rating }}</span>
            </div>
        </div>

        {{-- Divider --}}
        <div class="h-12 border-l border-gray-300"></div>

        {{-- Service Rating --}}
        <div class="text-center">
            <p class="text-sm text-gray-600 mb-2">Service</p>
            <div class="flex items-center justify-center gap-1">
                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="text-sm font-semibold text-gray-900">{{ $review->service_rating }}</span>
            </div>
        </div>
    </div>

    {{-- Review Text --}}
    <p class="text-gray-700 leading-relaxed">{{ $review->text}}</p>


</div>