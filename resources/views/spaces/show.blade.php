{{-- -Page dedicated to Showing the full page of a Space, including Editing and Deleting a space (If a BO) and if user
making a booking --}}
@extends('layouts.app')
@section('title', $space->title . ' - Sports Hub')
@section('content')
    <main class="container mx-auto p-8 rounded-2xl">
        {{-- -Info Section --}}
        <section class="mx-auto">
            <div class="flex items-center justify-start gap-2 mb-4 text-lg">
                <a href="{{ route('home') }}">
                    <img alt="Home Page" src="/images/home-icon.svg" height="18" width="18">
                </a>
                <svg class="w-5 h-5 pt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{route('spaces.index')}}">
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
                <h1 class="text-3xl font-bold mb-6">{{ $space->title }}</h1>
                {{-- Only show Delete and Edit Buttons if the user is the owner of that space --}}
                @auth
                    @if(auth()->user()->businessOwner && auth()->user()->businessOwner->id === $space->owner_id)
                        {{-- -Delete and Edit buttons --}}
                        <div class="flex gap-2.5">
                            {{-- -Delete Button --}}
                            <form action="{{ route('spaces.destroy', $space->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this space?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-1 bg-red-600 rounded-md hover:bg-red-700 transition cursor-pointer">
                                    <p class="text-white">Delete</p>
                                </button>
                            </form>
                            {{-- -Edit Button --}}
                            <a href="{{ route('spaces.edit', $space->id) }}"
                                class="px-4 py-1 bg-emerald-800 rounded-md hover:bg-emerald-200 transition flex items-center">
                                <p class="text-white hover:text-black">Edit</p>
                            </a>
                        </div>
                    @endif
                @endauth
            </div>
            <div class="flex">
                <div class="flex-[2]">
                    <div class="text-xl">
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
                        {{-- -Email flex --}}
                        <div class="flex items-center justify-start gap-2 mb-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <p class="font-bold">Email: </p>
                            <p>{{ $space->email }}</p>
                        </div>
                        {{-- -Phone Number flex --}}
                        <div class="flex items-center justify-start gap-2 mb-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <p class="font-bold">Phone: </p>
                            <p>{{ $space->phone_no }}</p>
                        </div>
                        {{-- -Owner flex --}}
                        <div class="flex items-center justify-start gap-2 mb-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <p class="font-bold">Owner: </p>
                            <p>{{ $space->owner->user->user_name }}</p>
                        </div>
                    </div>
                    {{-- Images --}}
                    <div class="flex gap-4 mt-4">
                        @foreach($space->media->take(4) as $index => $mediaItem)
                            <div class="flex flex-col items-center">
                                <img src="{{ $mediaItem->media_url }}"
                                     class="max-w-xs w-full h-48 rounded-lg mx-auto border-2"
                                     alt="Space Image {{ $index + 1 }}">
                                <span class="text-sm text-gray-600 mt-2">Fig. {{ $index + 1 }}</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Section About and Reviews and Calendar --}}
                    <div class="flex gap-4 mt-6 mb-36">
                        {{-- Section About and Revies, will have JS behaviour --}}
                        <div class="flex-1">
                            <div class="flex border-b-2 border-gray-200 pb-2.5">
                                <h2 id="about-tab"
                                    class="text-xl font-medium border-r-2 pr-2.5 text-emerald-800 cursor-pointer transition-colors">
                                    About</h2>
                                <h2 id="reviews-tab"
                                    class="text-xl font-medium pl-2.5 hover:text-emerald-800 cursor-pointer transition-colors">
                                    Reviews</h2>
                            </div>
                            {{-- About Content --}}
                            <div id="about-content" class="mt-3.5">
                                <p class="text-base">{{ $space->description }}</p>
                            </div>

                            {{-- Review Content --}}
                            <div id="reviews-content" class="mt-3.5 hidden"> {{-- Will initially be hidden --}}
                                <p class="text-base">Reviews section coming soon...</p>
                                {{-- Will add later --}}
                            </div>
                        </div>
                    </div>

                    {{-- Map Section --}}
                    <div>
                        <p class="text-xl font-semibold mb-1">Location: </p>
                        <div class="flex text-lg items-center justify-start gap-2 mb-2">
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
                <div class="flex-[1]">
                    @include('bookings.partials.calendar-widget', ['space' => $space])
                </div>
            </div>
        </section>
    </main>
    @include('bookings.modals.payment-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/spaces.show.js') }}"></script>
    <script src="{{ asset('js/booking.js') }}"></script>
@endpush