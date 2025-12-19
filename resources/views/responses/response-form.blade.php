{{-- filepath: resources/views/responses/response-form.blade.php --}}
<div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
    <h3 class="text-2xl font-semibold text-gray-900 mb-6">Respond to Review</h3>

    <form action="{{ route('responses.store') }}" method="POST" id="response-form">
        @csrf
        <input type="hidden" name="review_id" value="{{ $review->id }}">

        {{-- Response Text --}}
        <div class="mb-6">
            <label for="response-text" class="block text-lg font-medium text-gray-900 mb-2">
                Your Response
            </label>
            <textarea 
                name="text" 
                id="response-text" 
                rows="6" 
                maxlength="500"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                placeholder="Thank the customer and address their feedback..."
                required
            ></textarea>
            <div class="flex justify-end mt-1">
                <span id="response-char-count" class="text-sm text-gray-500">0/500 chars</span>
            </div>
            @error('text')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Buttons Cancel and Submit Response --}}
        <div class="flex justify-end gap-3">
            <button 
                type="button" 
                onclick="hideResponseForm()"
                class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button 
                type="submit" 
                class="px-6 py-2.5 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition-colors shadow-md hover:shadow-lg">
                Submit Response
            </button>
        </div>
    </form>
</div>


@push('scripts')
    <script src="{{ asset('js/response.js') }}"></script>
    <script src="{{ asset('js/review-form.js') }}"></script>
@endpush