{{-- -Page dedicated to Showing the full page of a Space, including Editing and Deleting a space (If a BO) and if user
making a booking --}}
@extends('layouts.app')
@push('head')
    <meta property="og:title" content="Sport Spaces - Sports Hub">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/logo.svg') }}">
@endpush



@section('title', $space->title . ' - Sports Hub')
@section('content')
@include('spaces.partials.delete')
    <main class="container mx-auto max-w-6xl p-8">
        {{-- -Info Section --}}
        <section>
            <div class="mx-auto flex items-center justify-start gap-2 mb-4 text-lg">
                <a href="{{ route('home') }}" class="text-emerald-600 hover:text-emerald-400">
                    <img alt="Home Page" src="/images/home-icon.svg" height="18" width="18">
                </a>
                <svg class="w-5 h-5 pt-0.5 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{route('spaces.index')}}" class="text-emerald-600 hover:text-emerald-400">
                    Sports Spaces
                </a>
                <svg class="w-5 h-5 pt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <p>
                    {{ $space->title }}
                </p>
            </div>
            {{-- Div that will be used for Buttons to Delete and Edit space --}}
            <div class="flex justify-between">
                <div class="flex items-center gap-8 mb-4">
                    <h1 class="text-3xl font-bold">{{ $space->title}}</h1>
                    @php
                        $averageRating = ($space->current_environment_rating +
                                         $space->current_equipment_rating +
                                         $space->current_service_rating) / 3;
                    @endphp
                    <div class="flex items-center gap-1 bg-yellow-100 px-2 py-1 rounded-lg ml-auto">
                        <svg class="w-4 h-4 text-yellow-500 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <span class="text-sm font-bold text-gray-800">{{ number_format($averageRating, 1) }} ({{ $space-> num_reviews}} reviews) </span>
                    </div>
                    @can('favorite',$space)
                        @php
                            $isFavorited = auth()->user()->customer->favoritedSpaces->contains($space->id);
                        @endphp
                        <button
                            id="favorite-btn"
                            data-space-id="{{ $space->id }}"
                            class="transition-transform hover:scale-110 hover:bg-gray-200 rounded-full p-2 focus:outline-none"
                            aria-label="Toggle favorite">
                            <svg class="w-8 h-8 {{ $isFavorited ? 'fill-red-500 stroke-red-500' : 'fill-none stroke-gray-400' }}" 
                            stroke-width="2" 
                            viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    @endcan
                </div>

                {{-- Only show Delete and Edit Buttons if the user is the owner of that space --}}
                @auth
                    @if(auth()->user()->businessOwner && auth()->user()->businessOwner->id === $space->owner_id)
                        {{-- -Delete and Edit buttons --}}
                        <div class="flex items-center gap-2.5">
                        {{-- Delete Button --}}
                        <form action="{{ route('spaces.destroy', $space->id) }}" method="POST" class="inline-block" id="delete-form-{{ $space->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" 
                                    onclick="openDeleteModal(event, {{ $space->id }})"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-base font-medium rounded-lg hover:bg-red-700 transition-colors shadow-sm hover:shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </form>

                            {{-- Edit Button --}}
                            <button type="button" onclick="window.location.href='{{ route('spaces.edit', $space->id) }}'"
                                class="inline-flex items-center px-4 py-2 bg-emerald-800 text-white text-base font-medium rounded-lg hover:bg-emerald-200 hover:text-black transition-colors shadow-sm hover:shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </button>

                            {{-- Manage Reservations Button --}}
                            <button type="button" onclick="window.location.href='{{ route('spaces.bookings', $space->id) }}'"
                                    class="inline-flex items-center px-4 py-2 bg-emerald-800 text-white text-base font-medium rounded-lg hover:bg-emerald-200 hover:text-black transition-colors shadow-sm hover:shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Manage Reservations
                            </button>
                        </div>
                    @endif
                @endauth
            </div>
            <div class="text-lg">
                {{-- -Address flex --}}
                <div class="flex items-center justify-start gap-2 mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <p class="font-bold">Address: </p>
                    <p>{{ $space->address }}</p>
                </div>
                {{-- -Sport Type flex --}}
                <div class="flex items-center justify-start gap-2 mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <p class="font-bold">Sport Type: </p>
                    <p>{{ $space->sportType->name }}</p>
                </div>

            </div>
            {{-- Images --}}
            @if($space->media->isNotEmpty())
                <div class="flex gap-4 mt-4">
                    @foreach($space->media->take(4) as $index => $mediaItem)
                        <div class="flex flex-col items-center">
                            <img src="{{ $mediaItem->media_url }}" class="max-w-xs w-full h-48 rounded-2xl mx-auto border-2"
                                alt="Space Image {{ $index + 1 }}">
                            <span class="text-sm text-gray-600 mt-2">Fig. {{ $index + 1 }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex">
                    <p class="text-xl text-red-400 my-4 justify-center items-center font-semibold">No Images Yet</p>
                </div>
            @endif

            @if($space->activeDiscounts && $space->activeDiscounts->count() > 0)
                <div onclick="openDiscountModal()" class="mt-6 cursor-pointer group relative overflow-hidden rounded-xl bg-gradient-to-r from-emerald-600 to-teal-500 p-6 shadow-lg transition-transform hover:scale-[1.01]">
                    <div class="relative z-10 flex items-center justify-between text-white">
                        <div class="flex items-center gap-4">
                            <div class="rounded-full bg-white/20 p-3 backdrop-blur-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold">Discounts Available!</h3>
                                <p class="text-emerald-50">There are active coupons for this space. Click to view them.</p>
                            </div>
                        </div>
                        <button class="rounded-lg bg-white px-5 py-2.5 font-bold text-emerald-700 shadow-sm transition group-hover:bg-emerald-50">
                            View Codes
                        </button>
                    </div>
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-white/10 blur-xl"></div>
                    <div class="absolute -bottom-6 left-10 h-32 w-32 rounded-full bg-white/10 blur-xl"></div>
                </div>
            @endif
            <div class="flex">
                <div class="flex-[5] mr-4">
                    {{-- Section About and Reviews and Calendar --}}
                    <div class="flex gap-4 mt-6 mb-24">
                        {{-- Section About and Revies, will have JS behaviour --}}
                        <div class="flex-1">
                            <div class="flex border-b-2 border-gray-200 pb-2.5">
                                <h2 id="about-tab"
                                    class="text-xl font-medium pr-2.5 text-emerald-800 cursor-pointer transition-colors">
                                    About</h2>
                                <h2 id="reviews-tab"
                                    class="text-xl font-medium pl-2.5 hover:text-emerald-800 cursor-pointer transition-colors">
                                    Reviews</h2>
                            </div>
                            {{-- About Content --}}
                            <div id="about-content" class="mt-3.5">
                                <p class="text-base">{{ $space->description }}</p>
                            </div>

                            {{-- Reviews Content --}}
                            <div id="reviews-content" class="mt-3.5 hidden"> {{-- Will initially be hidden --}}
                                @if($reviews->isNotEmpty())
                                    {{-- Include general reviews info --}}
                                    @include('reviews.partials.general-reviews-info',[
                                        'averageRating' => $averageRating,
                                        'avgEnvironment' => $avgEnvironment,
                                        'avgEquipment' => $avgEquipment,
                                        'avgService' => $avgService,
                                        'totalReviews' => $totalReviews
                                    ])

                                    {{-- Write Review button & Form (should only appear for customers, that reserved the space) --}}
                                    @auth
                                        @if (auth()->user()->customer)
                                            @php
                                                // need to check if the customer has a past reservation for this space
                                                $eligibleBooking = auth()->user()->customer->bookings()->where('space_id',$space->id)->where('is_cancelled',false)->whereHas('schedule',function($q){
                                                    $q->where('start_time', '<',now());
                                                })->whereDoesntHave('review')->first();
                                            @endphp

                                            @if($eligibleBooking)
                                                <div class="my-6">
                                                    {{-- Write Review Button --}}
                                                    <div id="write-review-btn-container" class="flex justify-center">
                                                        <button onclick="showReviewForm()" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition-colors shadow-md hover:shadow-lg">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                            </svg>
                                                            <p class="cursor-default">Write a Review</p>
                                                        </button>
                                                    </div>

                                                    {{-- Review Form (hidden by default) --}}
                                                    <div id="review-form-container" class="hidden">
                                                        @include('reviews.review-form',['space'=>$space,'bookingId' => $eligibleBooking->id])
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endauth
                                    {{-- Review List --}}
                                    <div class="space-y-4 mt-6">
                                        @foreach ( $reviews as $review )
                                            @include('reviews.partials.review-card',['review' => $review])
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center pt-8">
                                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                        </svg>
                                        <p class="text-gray-500 text-lg">No reviews yet</p>
                                        @auth
                                            @if (auth()->user()->customer)
                                                @php
                                                    // need to check if the customer has a past reservation for this space
                                                    $eligibleBooking = auth()->user()->customer->bookings()->where('space_id',$space->id)->where('is_cancelled',false)->whereHas('schedule',function($q){
                                                        $q->where('start_time', '<',now());
                                                    })->whereDoesntHave('review')->first();
                                                @endphp

                                                @if ($eligibleBooking)
                                                    <p class="text-gray-400 text-sm mt-2 mb-4">Be the first to review this space!</p>

                                                    <div id="write-first-review-btn">
                                                        {{-- Review Button --}}
                                                        <button onclick="showReviewForm()" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition-colors shadow-md hover:shadow-lg">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                            </svg>
                                                            <p class="cursor-default">Write the First Review</p>
                                                        </button>
                                                    </div>

                                                    <div id="review-form-container" class="hidden mt-6 max-w-3xl mx-auto">
                                                        @include('reviews.review-form',['space'=>$space,'bookingId' => $eligibleBooking->id])
                                                    </div>
                                                @else
                                                    <p class="text-gray-400 text-sm mt-2">Book this space to leave a review!</p>
                                                @endif
                                            @else
                                                <p class="text-gray-400 text-sm mt-2">Be the first to book and review this space!</p>
                                            @endif
                                        @else
                                            <p class="text-gray-400 text-sm mt-2">
                                                <a href="{{ route('login') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                                                    Sign in to book and review this space!
                                                </a>
                                            </p>
                                        @endauth
                                    </div>
                                    @endif
                            </div>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mt-4 mb-1">About Business Owner: </h3>
                    {{-- -Owner flex --}}
                    <div class="flex items-center justify-start gap-2 mb-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <p class="font-semibold">Owner: </p>
                        <p>{{ $space->owner->user->first_name }} {{ $space->owner->user->surname }}</p>
                    </div>
                    {{-- -Email flex --}}
                    <div class="flex items-center justify-start gap-2 mb-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <p class="font-semibold">Email: </p>
                        <p>{{ $space->email }}</p>
                    </div>
                    {{-- -Phone Number flex --}}
                    <div class="flex items-center justify-start gap-2 mb-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <p class="font-semibold">Phone: </p>
                        <p>{{ $space->phone_no }}</p>
                    </div>
                    {{-- Map Section --}}
                    <div>
                        <h3 class="text-xl font-bold mt-4 mb-1">Location: </h3>
                        <div class="flex items-center justify-start gap-2 mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <p>{{ $space->address }}</p>
                        </div>
                        @include('partials.map', [
                            'mapId' => 'spaceMap',
                            'spaces' => collect([$space]),
                            'latitude' => $space->latitude ?? 41.1579,
                            'longitude' => $space->longitude ?? -8.6291,
                            'zoom' => 15,
                            'height' => 'h-64',
                            'showPopupImage' => false,
                            'fitBoundsPadding' => 50
                        ])
                    </div>
                </div>
                @if(!Auth::check() or !auth()->user()->businessOwner)
                    <div class="flex-[3] mt-6">
                        @include('bookings.partials.calendar-widget', ['space' => $space])
                    </div>
                @endif
            </div>
        </section>
    </main>
    @include('bookings.modals.payment-modal')
    @include('bookings.modals.login-modal')
    @include('partials.discounts')
@endsection

@push('scripts')
    <script src="{{ asset('js/business-owner.js') }}"></script>
    <script src="{{ asset('js/booking.js') }}"></script>
    <script src="{{ asset('js/review.js') }}"></script>
    <script src="{{ asset('js/favoriteToggle.js') }}"></script>
    <script src="{{ asset('js/discounts.js') }}"></script>
@endpush