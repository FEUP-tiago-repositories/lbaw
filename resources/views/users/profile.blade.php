@extends('layouts.app')
@include('users.partials.delete')
@section('content')
    <div class="container mx-auto items-center">
        <div class="max-w-6xl mx-auto my-8">
            <div class="flex items-center ml-4 gap-2 mb-6 text-lg">
                <a href="{{ route('home') }}" class="text-emerald-600 hover:text-emerald-400">
                    <img alt="Home Page" src="/images/home-icon.svg" height="18" width="18">
                </a>
                <svg class="w-5 h-5 pt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('users.show', Auth::id()) }}" class="text-emerald-600 hover:text-emerald-400">
                    Profile of {{ Auth::user()->first_name }} {{ Auth::user()->surname }}
                </a>
            </div>
            <div class="flex gap-6">
                <div class="flex-1 bg-white shadow-lg rounded-2xl p-10">

                    {{-- Header --}}
                    <div class="flex items-center gap-8">
                        <img src="{{ $user->profile_pic_url ? asset($user->profile_pic_url) : 'https://via.placeholder.com/120' }}"
                            class="w-20 h-20 rounded-full object-cover border-gray-200 shadow">
                        <div class="space-y-1">
                            <h1 class="text-4xl font-bold text-gray-900">
                                {{ $user->first_name }} {{ $user->surname }}
                            </h1>
                            <p class="text-gray-500 text-lg">
                                Username: <span class="font-medium text-gray-700">{{ $user->user_name }}</span>
                            </p>
                            <p class="text-gray-600 text-lg flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ $user->email }}
                            </p>

                            <p class="text-gray-600 text-lg flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                Phone: {{ $user->phone_no }}
                            </p>
                        </div>
                    </div>

                    <hr class="my-8">

                    {{-- Info --}}
                    <div class="space-y-4">
                        <h2 class="text-2xl font-semibold text-gray-800">Account Information</h2>

                        <p class="text-lg flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="font-medium text-gray-700">Birth Date:</span>
                            {{ $user->birth_date }}
                        </p>

                        <p class="text-lg flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="font-medium text-gray-700">Role:</span>
                            @if($user->customer)
                                <span class="font-bold">Customer</span>
                            @elseif($user->businessOwner)
                                <span class="font-bold">Business Owner</span>
                            @else
                                <span class="font-bold">Not defined</span>
                            @endif
                        </p>
                    </div>

                    <hr class="my-8">

                    {{-- Buttons --}}
                    <div class="flex gap-4">
                        <a href="{{ route('users.edit', $user->id) }}"
                            class="px-6 py-3 bg-emerald-900 text-white rounded-lg hover:bg-emerald-200 hover:text-black transition shadow text-center font-medium ">
                            Edit Profile
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button
                                class="px-6 py-3 bg-red-500 text-white rounded-lg hover:bg-red-300 hover:text-black transition shadow text-center font-medium">
                                Log Out
                            </button>
                        </form>

                        <button onclick="openDeleteModal()"
                            class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-300 hover:text-black transition shadow text-center font-medium">
                            Delete Account
                        </button>
                    </div>
                </div>

                {{-- Right Column: Role-Specific Actions --}}
                <div class="w-72">
                    @if($user->customer)
                        <div class="bg-white border-2 border-emerald-200 rounded-xl p-6 shadow-lg">
                            <div class="flex flex-col gap-3">
                                <a href="{{ route('bookings.index', ['user_id' => auth()->id()]) }}"
                                    class="px-5 py-2.5 bg-emerald-900 text-white rounded-lg hover:bg-emerald-200 hover:text-black transition shadow text-center text-[19px] font-medium">
                                    My Reservations
                                </a>

                                <a href="{{ route('notifications.index') }}"
                                    class="relative px-5 py-2.5 bg-emerald-900 text-white rounded-lg hover:bg-emerald-200 hover:text-black transition shadow text-center text-[19px] font-medium group">

                                    Notifications

                                    @if($unreadCount > 0)
                                        <span
                                            class="absolute -top-2 -right-2 inline-flex items-center justify-center w-7 h-7 text-xs font-bold text-white bg-red-600 border-2 border-white rounded-full z-10">
                                            {{ $unreadCount }}
                                        </span>
                                    @endif
                                </a>
                                @auth
                                    @if(auth()->user()->customer)
                                        <a href="{{ route('favorites.index') }}"
                                            class="px-5 py-2.5 bg-emerald-900 text-white rounded-lg hover:bg-emerald-200 hover:text-black transition shadow text-center text-[19px] font-medium flex items-center justify-center gap-3">
                                            <svg class="w-6 h-6 fill-current" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                            <span>My Favorites</span>
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </div>


                    @elseif($user->businessOwner)
                        <div class="bg-white border-2 border-emerald-200 rounded-xl p-6 shadow-lg text-center font-medium">
                            <div class="flex flex-col gap-3">
                                <a href="{{ route('spaces.create') }}"
                                    class="px-5 py-2.5 bg-emerald-900 text-white rounded-lg hover:bg-emerald-200 hover:text-black transition shadow">
                                    Create New Space
                                </a>

                                <a href="{{ route('spaces.bookings.select') }}"
                                    class="px-5 py-2.5 bg-emerald-900 text-white rounded-lg hover:bg-emerald-200 hover:text-black transition shadow">
                                    Manage Reservations and Schedules
                                </a>
                                <a href="{{ route('notifications.index') }}"
                                    class="relative px-5 py-2.5 bg-emerald-900 text-white rounded-lg hover:bg-emerald-200 hover:text-black transition shadow">
                                    Notifications

                                    @if($unreadCount > 0)
                                        <span
                                            class="absolute -top-2 -right-2 inline-flex items-center justify-center w-7 h-7 text-xs font-bold text-white bg-red-600 border-2 border-white rounded-full z-10">
                                            {{ $unreadCount }}
                                        </span>
                                    @endif
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Admin's spaces --}}
        @if($user->businessOwner && $user->spaces->count() > 0)
            <div class="max-w-6xl mx-auto mt-12 mb-12">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Your Sports Spaces</h2>
                <div class="flex overflow-x-auto gap-10 pb-4">
                    @foreach ($user->spaces as $space)
                        <div class="shrink-0 w-64">
                            @include('spaces.partials.space-card', ['space' => $space])
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection