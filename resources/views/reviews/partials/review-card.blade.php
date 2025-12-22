<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-4">
    {{-- Review Header --}}
    <div class="flex items-start justify-between mb-4">
        {{-- Left: User info and overall rating --}}
        <div class="flex items-start gap-4">
            {{-- Profile Picture --}}
            <div class="shrink-0 w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                @if($review->customer->user->profile_pic_url)
                    <img src="{{ $review->customer->user->profile_pic_url }}" alt="profile picture" class="w-full h-full object-cover"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <svg class="w-8 h-8 text-gray-400 hidden" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                              clip-rule="evenodd"/>
                    </svg>
                @else
                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                              clip-rule="evenodd"/>
                    </svg>
                @endif
            </div>

            {{-- User name and overall rating --}}
            <div class="items-center">
                <p class="font-semibold text-gray-900">{{ $review->customer->user->user_name}}</p>
                <div class="flex items-center mt-1">
                    @php
                        $overallRating = ($review->environment_rating + $review->equipment_rating + $review->service_rating) / 3;
                    @endphp
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= round($overallRating) ? 'text-yellow-400' : 'text-gray-300' }}"
                             fill="currentColor" viewBox="0 0 20 20">
                            <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
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
    <div class="flex items-center justify-around mb-4 py-2 bg-gray-200 rounded-lg shadow-sm ">
        {{-- Environment rating --}}
        <div class="flex-1 text-center">
            <p class="text-sm text-gray-600 mb-1">Environment</p>
            <div class="flex items-center justify-center gap-1">
                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                    <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span class="text-sm font-semibold text-gray-900">{{ $review->environment_rating }}</span>
            </div>
        </div>

        {{-- Divider --}}
        <div class="h-12 border-l border-gray-400"></div>

        {{-- Equipment rating --}}
        <div class="flex-1 text-center">
            <p class="text-sm text-gray-600 mb-2">Equipment</p>
            <div class="flex items-center justify-center gap-1">
                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                    <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span class="text-sm font-semibold text-gray-900">{{ $review->equipment_rating }}</span>
            </div>
        </div>

        {{-- Divider --}}
        <div class="h-12 border-l border-gray-400"></div>

        {{-- Service Rating --}}
        <div class="flex-1 text-center">
            <p class="text-sm text-gray-600 mb-2">Service</p>
            <div class="flex items-center justify-center gap-1">
                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                    <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span class="text-sm font-semibold text-gray-900">{{ $review->service_rating }}</span>
            </div>
        </div>
    </div>

    {{-- Review Text --}}
    <p class="text-gray-700 leading-relaxed">{{ $review->text}}</p>

    {{-- Response Button --}}
    {{-- this button should only appear if the space owner of the review is the current logged in BO --}}
    @can('createForReview', [App\Models\Response::class, $review])
        <div id="write-response-btn-container" class="flex justify-center mt-4">
            <button onclick="showResponseForm()"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition-colors shadow-md hover:shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <p class="cursor-defautl">Respond to Review</p>
            </button>
        </div>

        {{-- Response Form (hidden by default, will be handled by JS) --}}
        <div id="response-form-container" class="hidden mt-6 ml-16">
            @include('reviews.response-form', ['review' => $review])
        </div>
    @endcan

    {{-- Response Section --}}

    {{-- need to check if the review has a response associated and if so show it --}}
    @if ($review->response)
        <div class="mt-4 ml-16 pl-4 border-l-4 border-emerald-500 rounded-r-lg p-4 bg-emerald-50">
            <div class="flex items-start gap-3">
                {{-- Avatar icon --}}
                <div class="shrink-0">
                    <div class="w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                {{-- Response Content itself --}}
                <div class="flex-1">
                    <div class="mb-1">
                        <p class="font-semibold text-gray-900">
                            Response from {{ $review->response->owner->user->user_name }}
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ $review->response->time_stamp->format('F j, Y') }}
                        </p>
                    </div>
                    <p class="text-gray-700 text-sm leading-relaxed mt-2">
                        {{ $review->response->text }}
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script src="{{ asset('js/response.js') }}"></script>
@endpush