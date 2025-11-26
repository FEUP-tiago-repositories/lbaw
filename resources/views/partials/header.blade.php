<nav
    class="navbar h-16 max-w-7xl mx-auto flex items-center px-5 pt-5 pb-4 justify-between bg-white shadow-md rounded-2xl mt-3">
    <div class="flex items-center">
        <a href="{{ route('home') }}"
            class="text-lg font-medium hover:text-green-700 transition-colors duration-300 ease-in-out">Home</a>
        <a href="{{ route('spaces.index') }}"
            class="ml-6 text-lg font-medium hover:text-green-700 transition-colors duration-300 ease-in-out">Spaces</a>
    </div>

    <form action="{{ route('spaces.search') }}" method="GET" class="flex items-center">
            <input type="text" name="q" placeholder="Search Spaces or Sports..." class="border p-2 rounded-md ">
            <button type="submit" class="bg-green-500 text-white p-2 font-bold rounded-md">Search</button>
    </form>


    <div class="flex items-center">
        @auth {{-- -If user is authenticated --}}
            <a href="{{ route('logout') }}"
                class="text-lg font-medium hover:text-green-700 transition-colors duration-300 ease-in-out"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @else
            <a href="{{ route('login') }}"
                class="text-lg font-medium hover:text-green-700 transition-colors duration-300 ease-in-out">Login</a>
            <a href="{{ route('register') }}"
                class="ml-6 text-lg font-medium hover:text-green-700 transition-colors duration-300 ease-in-out">Register</a>
        @endauth
    </div>
</nav>