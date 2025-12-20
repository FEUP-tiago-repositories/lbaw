@extends('layouts.app')
@include('users.partials.delete')
@section('content')
    <div class="container mx-auto items-center">
        <div class="max-w-5xl mx-auto my-8">
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
                <div class="flex-1 bg-white shadow-lg rounded-3xl p-8">

                    {{-- Header --}}
                    <div class="flex items-center gap-8">
                        <img src="{{ $user->profile_pic_url ? asset($user->profile_pic_url) : 'https://via.placeholder.com/120' }}"
                            class="w-20 h-20 rounded-full object-cover border-gray-200 shadow">
                        <div class="space-y-2">
                            <h1 class="text-4xl font-bold text-gray-900">
                                {{ $user->first_name }} {{ $user->surname }}
                            </h1>
                            <p class="text-gray-500 text-lg">
                                Username: <span class="font-medium text-gray-700">{{ $user->user_name }}</span>
                            </p>
                        </div>
                    </div>

                    <hr class="my-6">

                    {{-- Info --}}
                    <div class="space-y-2">
                        <h2 class="text-2xl font-semibold text-gray-800">Account Information</h2>
                        <p class="text-gray-700 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="font-medium">Email: </span>{{ $user->email }}
                        </p>

                        <p class="text-gray-700  flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span class="font-medium">Phone: </span>{{ $user->phone_no }}
                        </p>
                        <p class="text-gray-700 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="font-medium">Birth Date:</span>{{ $user->birth_date }}
                        </p>

                        <p class="text-gray-700 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="font-medium">Role:</span>
                            @if($user->customer)
                                <span class="font-bold">Customer</span>
                            @elseif($user->businessOwner)
                                <span class="font-bold">Business Owner</span>
                            @else
                                <span class="font-bold">Not defined</span>
                            @endif
                        </p>
                    </div>

                    <hr class="my-6">

                    {{-- Buttons --}}
                    <div class="flex gap-4">
                        <a href="{{ route('users.edit', $user->id) }}"
                            class="px-6 py-3 bg-emerald-900 text-white rounded-xl hover:bg-emerald-200 hover:text-black transition shadow text-center font-medium inline-flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <!-- corpo do lápis -->
                                <path d="M16 20H5a1 1 0 0 1-1-1v-3.59a1 1 0 0 1 .29-.71l9.17-9.17a1 1 0 0 1 1.41 0l3.59 3.59a1 1 0 0 1 0 1.41L9 19.71a1 1 0 0 1-.71.29z"></path>
                                <!-- ponta apagador -->
                                <path d="M15 5l4 4"></path>
                            </svg>
                            Edit Profile
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button
                                class="px-6 py-3 bg-red-500 text-white rounded-xl hover:bg-red-300 hover:text-black transition shadow text-center font-medium inline-flex items-center gap-1">
                                Log Out
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="22" height="22" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <!-- porta -->
                                    <path d="M10 5H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h4"/>
                                    <!-- seta de saída -->
                                    <path d="M14 12h7"/>
                                    <path d="M18 8l3 4-3 4"/>
                                </svg>
                            </button>
                        </form>

                        <button onclick="openDeleteModal()"
                            class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-300 hover:text-black transition shadow text-center font-medium inline-flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="18" height="18" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <!-- tampa + pega -->
                                <path d="M9 4h6M10 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1" />
                                <!-- corpo do caixote -->
                                <rect x="6" y="7" width="12" height="13" rx="2" />
                                <!-- linhas internas -->
                                <path d="M10 11v6M14 11v6" />
                                <!-- linha de cima (onde passa a tampa) -->
                                <path d="M5 7h14" />
                            </svg>
                            Delete Account
                        </button>
                    </div>
                </div>

                {{-- Right Column: Role-Specific Actions --}}
                <div class="w-72 text-lg">
                    @if($user->customer)
                        <div class="bg-white border-2 border-emerald-200 rounded-3xl p-6 shadow-lg">
                            <div class="flex flex-col gap-3">
                                <a href="{{ route('bookings.index', ['user_id' => auth()->id()]) }}"
                                    class="px-4 py-2 bg-emerald-900 text-white rounded-xl hover:bg-emerald-200 hover:text-black transition shadow text-center font-medium">
                                    My Reservations
                                </a>

                                <a href="{{ route('notifications.index') }}"
                                    class="relative inline-flex items-center gap-1 justify-center py-2 bg-emerald-900 text-white rounded-xl hover:bg-emerald-200 hover:text-black transition shadow text-center font-medium group">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                        <path d="M5.85 3.5a.75.75 0 0 0-1.117-1 9.719 9.719 0 0 0-2.348 4.876.75.75 0 0 0 1.479.248A8.219 8.219 0 0 1 5.85 3.5ZM19.267 2.5a.75.75 0 1 0-1.118 1 8.22 8.22 0 0 1 1.987 4.124.75.75 0 0 0 1.48-.248A9.72 9.72 0 0 0 19.266 2.5Z" />
                                        <path fill-rule="evenodd" d="M12 2.25A6.75 6.75 0 0 0 5.25 9v.75a8.217 8.217 0 0 1-2.119 5.52.75.75 0 0 0 .298 1.206c1.544.57 3.16.99 4.831 1.243a3.75 3.75 0 1 0 7.48 0 24.583 24.583 0 0 0 4.83-1.244.75.75 0 0 0 .298-1.205 8.217 8.217 0 0 1-2.118-5.52V9A6.75 6.75 0 0 0 12 2.25ZM9.75 18c0-.034 0-.067.002-.1a25.05 25.05 0 0 0 4.496 0l.002.1a2.25 2.25 0 1 1-4.5 0Z" clip-rule="evenodd" />
                                    </svg>
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


                    @elseif($user->businessOwner)
                        <div class="bg-white border-2 border-emerald-200 rounded-3xl p-6 shadow-lg text-center font-medium">
                            <div class="flex flex-col gap-3">
                                <a href="{{ route('spaces.create') }}"
                                    class="px-4 py-2 bg-emerald-900 text-white rounded-xl hover:bg-emerald-200 hover:text-black transition shadow">
                                    Create New Space
                                </a>

                                <a href="{{ route('spaces.bookings.select') }}"
                                    class="px-4 py-2 bg-emerald-900 text-white rounded-xl hover:bg-emerald-200 hover:text-black transition shadow">
                                    Manage Reservations and Schedules
                                </a>
                                <a href="{{ route('notifications.index') }}"
                                    class="relative inline-flex items-center gap-1 justify-center px-4 py-2 bg-emerald-900 text-white rounded-xl hover:bg-emerald-200 hover:text-black transition shadow">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                        <path d="M5.85 3.5a.75.75 0 0 0-1.117-1 9.719 9.719 0 0 0-2.348 4.876.75.75 0 0 0 1.479.248A8.219 8.219 0 0 1 5.85 3.5ZM19.267 2.5a.75.75 0 1 0-1.118 1 8.22 8.22 0 0 1 1.987 4.124.75.75 0 0 0 1.48-.248A9.72 9.72 0 0 0 19.266 2.5Z" />
                                        <path fill-rule="evenodd" d="M12 2.25A6.75 6.75 0 0 0 5.25 9v.75a8.217 8.217 0 0 1-2.119 5.52.75.75 0 0 0 .298 1.206c1.544.57 3.16.99 4.831 1.243a3.75 3.75 0 1 0 7.48 0 24.583 24.583 0 0 0 4.83-1.244.75.75 0 0 0 .298-1.205 8.217 8.217 0 0 1-2.118-5.52V9A6.75 6.75 0 0 0 12 2.25ZM9.75 18c0-.034 0-.067.002-.1a25.05 25.05 0 0 0 4.496 0l.002.1a2.25 2.25 0 1 1-4.5 0Z" clip-rule="evenodd" />
                                    </svg>
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
            <div class="max-w-5xl mx-auto mb-12">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Your Sports Spaces</h2>
                <div class="flex overflow-x-auto gap-10 pb-4">
                    @foreach ($user->spaces as $space)
                        <div class="shrink-0 w-64">
                            @include('spaces.partials.space-card', ['space' => $space])
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif($user->customer)
            {{-- Customer: Mostra favoritos --}}
            <section class="max-w-5xl mx-auto mb-12">
                <h2 class="text-2xl font-semibold mb-4">My Favorite Spaces</h2>

                @if($favoritedSpaces && $favoritedSpaces->isNotEmpty())
                    <div class="relative">
                        <!-- Gradiente Esquerdo -->
                        <div id="favorites-gradient-left" class="absolute left-0 top-0 bottom-0 w-24 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none opacity-0 transition-opacity duration-300"></div>

                        <!-- Seta Esquerda -->
                        <button id="favorites-scroll-left" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white rounded-full p-3 shadow-lg hover:bg-gray-50 transition opacity-0 pointer-events-none">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <!-- Container com Scroll Horizontal -->
                        <div id="favorites-scroll-container" class="flex overflow-x-auto gap-6 pb-4 scroll-smooth scrollbar-hide"
                             style="-ms-overflow-style: none; scrollbar-width: none;">
                            @foreach($favoritedSpaces as $space)
                                <div class="shrink-0 w-[250px]">
                                    @include('spaces.partials.space-card', ['space' => $space])
                                </div>
                            @endforeach
                        </div>

                        <!-- Gradiente Direito -->
                        <div id="favorites-gradient-right" class="absolute right-0 top-0 bottom-0 w-24 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none transition-opacity duration-300"></div>

                        <!-- Seta Direita -->
                        <button id="favorites-scroll-right" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white rounded-full p-3 shadow-lg hover:bg-gray-50 transition">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                @else
                    <div class="text-center py-16 bg-gray-50 rounded-xl">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <p class="text-xl text-gray-600 mb-2">No favorite spaces yet</p>
                        <p class="text-gray-500 mb-4">Start exploring and add your favorite spaces!</p>
                        <a href="{{ route('spaces.index') }}" class="inline-block bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700 transition">
                            Explore Spaces
                        </a>
                    </div>
                @endif
            </section>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/horizontal-scroll.js') }}"></script>
@endpush