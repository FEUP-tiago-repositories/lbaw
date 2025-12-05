<nav class="navbar h-20 mx-auto text-xl flex items-center px-20 py-4 justify-between bg-white shadow-md text-emerald-800 font-semibold transition-colors duration-300 ease-in-out">
    <div>
        <a href="{{ route('home') }}"
            class="hover:text-emerald-400">Home</a>
        <a href="{{ route('spaces.index') }}"
            class="ml-6 hover:text-emerald-400">Sports Spaces</a>
    </div>

    {{-- Input de search --}}
    <form action="{{ route('spaces.search') }}" method="GET" class="flex gap-2 items-center">
        <div class="relative font-normal">
            <svg class="w-5 h-5 absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="q" placeholder="Search spaces or sports..." class="border focus:outline-1 focus:outline-emerald-400 italic p-2 pl-12 pr-15 rounded-xl w-full">
        </div>
        <button type="submit" class="bg-emerald-800 font-medium text-white py-2 px-5 rounded-xl hover:bg-emerald-200 hover:text-black">Search</button>
    </form>

    <div>
        @auth {{-- -If user is authenticated --}}
            <a href="{{ route('users.show', Auth::id()) }}" class="hover:text-emerald-400">
                My Profile
            </a>
            <a href="{{ route('logout') }}"
                class="ml-6 hover:text-emerald-400"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @else
            <a href="{{ route('login') }}"
                class="hover:text-emerald-400">Login</a>
            <a href="{{ route('register') }}"
                class="ml-6 hover:text-emerald-400">Register</a>
        @endauth
    </div>
</nav>