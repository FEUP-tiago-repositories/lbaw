{{-- -Page dedicated to Showing the full page of a Space, including Editing and Deleting a space (If a BO) and if user
making a booking --}}
@extends('layouts.app')
@section('title', $space->title . ' - Sports Hub')
@section('content')
    <main class="container mx-auto px-8 py-8 bg-white rounded-2xl mt-2.5 shadow">
        {{-- -Info Section --}}
        <section class="mx-auto">
            {{-- Div that will be used for Buttons to Delete and Edit space --}}
            <div class="flex justify-between">
                <h1 class="text-3xl font-bold mb-6">{{ $space->title }}</h1>
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
                        class="px-4 py-1 bg-green-600 rounded-md hover:bg-green-700 transition flex items-center">
                        <p class="text-white">Edit</p>
                    </a>
                </div>
            </div>
            {{-- -Address flex --}}
            <div class="flex gap-3.5">
                <p class="text-2xl font-bold">Address: </p>
                <p class="text-2xl underline">{{ $space->address }}</p>
            </div>
            {{-- -Sport Type flex --}}
            <div class="flex gap-3.5">
                <p class="text-2xl font-bold">Sport Type: </p>
                <p class="text-2xl underline">{{ $space->sportType->name }}</p>
            </div>
            {{-- -Email flex --}}
            <div class="flex gap-3.5">
                <p class="text-2xl font-bold">Email: </p>
                <p class="text-2xl underline">{{ $space->email }}</p>
            </div>
            {{-- -Phone Number flex --}}
            <div class="flex gap-3.5">
                <p class="text-2xl font-bold">Phone: </p>
                <p class="text-2xl underline">{{ $space->phone_no }}</p>
            </div>
            {{-- -Owner flex --}}
            <div class="flex gap-3.5">
                <p class="text-2xl font-bold">Owner: </p>
                <p class="text-2xl underline">{{ $space->owner->user->user_name }}</p>
            </div>
            {{-- Images --}}
            <div class="flex gap-4 mt-4">
                @for ($i = 0; $i < 3; $i++)
                    <div class="flex flex-col items-center">
                        <img src="{{ asset('images/sportsplace.jpg') }}"
                            class="max-w-xs w-full h-auto rounded-lg mx-auto border-2" alt="Space Image {{ $i + 1 }}">
                        <span class="text-sm text-gray-600 mt-2">Fig. {{ $i + 1 }}</span>
                    </div>
                @endfor
            </div>
            {{-- Section About and Reviews and Calendar --}}
            <div class="flex gap-4 mt-6">
                {{-- Section About and Revies, will have JS behaviour --}}
                <div class="flex-1">
                    <div class="flex border-b-2 border-gray-200 pb-2.5">
                        <h2 id="about-tab"
                            class="text-xl font-medium border-r-2 pr-2.5 text-green-700 cursor-pointer transition-colors">
                            About</h2>
                        <h2 id="reviews-tab"
                            class="text-xl font-medium pl-2.5 hover:text-green-700 pb-2 cursor-pointer transition-colors">
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
            {{-- -Where the calendar-widget will be placed when done --}}
            <div class="lg:col-span-1">
                <div class="sticky top-8">
                    @include('bookings.partials.calendar-widget', ['space' => $space])
                </div>
            </div>
        </section>
    </main>
    {{-- @include(bookings.modals.payment-modal) --}}
@endsection

@push('scripts')
    <script src="{{ asset('js/spaces.show.js') }}"></script>
    <script src="{{ asset('js/booking.js') }}"></script>
@endpush