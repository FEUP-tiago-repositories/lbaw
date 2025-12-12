<div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
    <h3 class="text-2xl font-semibold text-gray-900 mb-6">Write a Review for {{ $space->title }}</h3>

    <form action="{{ route('reviews.store') }}" method="POST" id="review-form">
        @csrf
        <input type="hidden" name="booking_id" value="{{ $bookingId }}">

        {{-- Rate the Experience --}}
        <div mb="mb-6">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Rate your Experience</h4>

            {{-- Environment Rating --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Environment</label>
                <div class="flex items-center gap-2">
                    <div class="flex gap-1" id="environment-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <svg onclick="setRating('environment', {{ $i }})" 
                                 class="w-8 h-8 cursor-pointer transition-colors star-icon text-gray-300 hover:text-yellow-400" 
                                 fill="currentColor" viewBox="0 0 20 20"
                                 data-rating="{{ $i }}">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <span id="environment-label" class="text-sm text-gray-500">Not rated</span>
                </div>
                <input type="hidden" name="environment_rating" id="environment-rating" required>
                @error('environment_rating')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Equipment Rating --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Equipment</label>
                <div class="flex items-center gap-2">
                    <div class="flex gap-1" id="equipment-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <svg onclick="setRating('equipment', {{ $i }})" 
                                 class="w-8 h-8 cursor-pointer transition-colors star-icon text-gray-300 hover:text-yellow-400" 
                                 fill="currentColor" viewBox="0 0 20 20"
                                 data-rating="{{ $i }}">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <span id="equipment-label" class="text-sm text-gray-500">Not rated</span>
                </div>
                <input type="hidden" name="equipment_rating" id="equipment-rating" required>
                @error('equipment_rating')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Service Rating --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Service</label>
                <div class="flex items-center gap-2">
                    <div class="flex gap-1" id="service-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <svg onclick="setRating('service', {{ $i }})" 
                                 class="w-8 h-8 cursor-pointer transition-colors star-icon text-gray-300 hover:text-yellow-400" 
                                 fill="currentColor" viewBox="0 0 20 20"
                                 data-rating="{{ $i }}">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <span id="service-label" class="text-sm text-gray-500">Not rated</span>
                </div>
                <input type="hidden" name="service_rating" id="service-rating" required>
                @error('service_rating')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Review Text --}}
        <div class="mb-6">
            <label for="review-text" class="block text-lg font-medium text-gray-900 mb-2">Your Review</label>

            <textarea
                name="text"
                id="review-text"
                rows="6"
                maxlength="500"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-700 focus:border-emerald-700 transition-colors"
                placeholder="Share your experience at this space..."
                required
            >
            </textarea>
            <div class="flex justify-end mt-1">
                <span id="char-count" class="text-sm text-gray-500">0/500 chars</span>
            </div>
            @error('text')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Buttons Cancel and Submit Review --}}
        <div class="flex justify-end gap-3">
            <button type="button" onclick="hideReviewForm()" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition-colors shadow-md hover:shadow-lg">
                Submit Review
            </button>
        </div>
    </form>
</div>

@push('scripts')
    <script src="{{ asset('js/review.js') }}"></script>
    <script src="{{ asset('js/review-form.js') }}"></script>
@endpush