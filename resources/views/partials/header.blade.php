<nav class="navbar h-20 mx-auto text-lg flex items-center px-4 md:px-8 xl:px-32 2xl:px-64 py-4 justify-between bg-white shadow-md text-emerald-800 font-semibold transition-colors duration-300 ease-in-out">
    <div class="flex items-center gap-6">
        <a href="{{ route('home') }}" class="flex items-center">
            <img src="{{ asset('images/logo2.svg') }}" alt="SportsHub Logo" class="h-9 w-auto">
        </a>
        <div class="hidden lg:flex items-center gap-6">
            <a href="{{ route('spaces.index') }}" class="ml-6 hover:text-emerald-400">Sports Spaces</a>
        </div>
    </div>

    {{-- Input de search - Desktop --}}
    <form action="{{ route('spaces.search') }}" method="GET" class="hidden md:flex gap-2 items-center">
        <div class="relative font-normal">
            <svg class="w-5 h-5 absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="q" placeholder="Search spaces..." class="border focus:outline-1 focus:outline-emerald-400 italic p-2 pl-12 pr-4 rounded-xl w-full">
        </div>
        <button type="submit" class="bg-emerald-800 font-medium text-white py-2 px-5 rounded-xl hover:bg-emerald-200 hover:text-black">Search</button>
    </form>

    {{-- Desktop menu --}}
    <div class="hidden lg:flex items-center gap-4">
        @auth
            <a href="{{ route('users.show', Auth::id()) }}" class="hover:text-emerald-400">
                My Profile
            </a>
            @php
                $unreadCount = Auth::user()->notifications()->where('is_read', false)->count();
            @endphp
            <a href="{{ route('notifications.index') }}" class="relative inline-flex items-center hover:text-emerald-400">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path d="M5.85 3.5a.75.75 0 0 0-1.117-1 9.719 9.719 0 0 0-2.348 4.876.75.75 0 0 0 1.479.248A8.219 8.219 0 0 1 5.85 3.5ZM19.267 2.5a.75.75 0 1 0-1.118 1 8.22 8.22 0 0 1 1.987 4.124.75.75 0 0 0 1.48-.248A9.72 9.72 0 0 0 19.266 2.5Z" />
                    <path fill-rule="evenodd" d="M12 2.25A6.75 6.75 0 0 0 5.25 9v.75a8.217 8.217 0 0 1-2.119 5.52.75.75 0 0 0 .298 1.206c1.544.57 3.16.99 4.831 1.243a3.75 3.75 0 1 0 7.48 0 24.583 24.583 0 0 0 4.83-1.244.75.75 0 0 0 .298-1.205 8.217 8.217 0 0 1-2.118-5.52V9A6.75 6.75 0 0 0 12 2.25ZM9.75 18c0-.034 0-.067.002-.1a25.05 25.05 0 0 0 4.496 0l.002.1a2.25 2.25 0 1 1-4.5 0Z" clip-rule="evenodd" />
                </svg>
                @if($unreadCount > 0)
                    <span class="absolute -top-2 -right-2 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-600 border-2 border-white rounded-full">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>
            <a href="{{ route('logout') }}"
               class="ml-2 hover:text-emerald-400 inline-flex items-center gap-1"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 5H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h4"/>
                    <path d="M14 12h7"/>
                    <path d="M18 8l3 4-3 4"/>
                </svg>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @else
            <a href="{{ route('login') }}" class="hover:text-emerald-400">Login</a>
            <a href="{{ route('register') }}" class="ml-6 hover:text-emerald-400">Register</a>
        @endauth
    </div>

    {{-- Hamburger button --}}
    <button id="hamburger" class="lg:hidden flex flex-col gap-1.5 p-2" aria-label="Menu">
        <span class="block w-6 h-0.5 bg-emerald-800 transition-all duration-300"></span>
        <span class="block w-6 h-0.5 bg-emerald-800 transition-all duration-300"></span>
        <span class="block w-6 h-0.5 bg-emerald-800 transition-all duration-300"></span>
    </button>
</nav>

{{-- Mobile menu --}}
<div id="navMenu" class="fixed top-20 right-0 w-full bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out lg:hidden z-50">
    <div class="flex flex-col p-6 gap-4">
        {{-- Search mobile --}}
        <form action="{{ route('spaces.search') }}" method="GET" class="flex flex-col gap-2 md:hidden">
            <div class="relative font-normal">
                <svg class="w-5 h-5 absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="q" placeholder="Search spaces..." class="border focus:outline-1 focus:outline-emerald-400 italic p-2 pl-12 pr-4 rounded-xl w-full">
            </div>
            <button type="submit" class="bg-emerald-800 font-medium text-white py-2 px-5 rounded-xl hover:bg-emerald-200 hover:text-black">Search</button>
        </form>

        <a href="{{ route('spaces.index') }}" class="text-emerald-800 font-semibold hover:text-emerald-400 py-2">Sports Spaces</a>

        @auth
            <a href="{{ route('users.show', Auth::id()) }}" class="text-emerald-800 font-semibold hover:text-emerald-400 py-2">My Profile</a>

            <a href="{{ route('notifications.index') }}" class="text-emerald-800 font-semibold hover:text-emerald-400 py-2 flex items-center gap-2">
                Notifications
                @php
                    $unreadCount = Auth::user()->notifications()->where('is_read', false)->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-600 rounded-full">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>

            <a href="{{ route('logout') }}"
               class="text-emerald-800 font-semibold hover:text-emerald-400 py-2"
               onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                Logout
            </a>
            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @else
            <a href="{{ route('login') }}" class="text-emerald-800 font-semibold hover:text-emerald-400 py-2">Login</a>
            <a href="{{ route('register') }}" class="text-emerald-800 font-semibold hover:text-emerald-400 py-2">Register</a>
        @endauth
    </div>
</div>
