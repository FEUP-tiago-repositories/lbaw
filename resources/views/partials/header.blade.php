<nav class="navbar h-18 mx-auto text-xl flex items-center px-20 py-4 justify-between bg-white shadow-md text-green-800 font-semibold transition-colors duration-300 ease-in-out">
    <div>
        <a href="{{ route('home') }}"
            class="hover:text-green-400">Home</a>
        <a href="{{ route('spaces.index') }}"
            class="ml-6 hover:text-green-400">Spaces</a>
    </div>

    <form action="{{ route('spaces.search') }}" method="GET">
            <input type="text" name="q" placeholder="Search Spaces or Sports..." class="border p-2 rounded-md ">
            <button type="submit" class="bg-green-500 text-white p-2 font-bold rounded-md">Search</button>
    </form>

    <div>
        @auth {{-- -If user is authenticated --}}
            <a href="{{ route('logout') }}"
                class="hover:text-green-400"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @else
            <a href="{{ route('login') }}"
                class="hover:text-green-400">Login</a>
            <a href="{{ route('register') }}"
                class="ml-6 hover:text-green-400">Register</a>
        @endauth
    </div>
</nav>