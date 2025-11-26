<nav
    class="navbar h-18 mx-auto text-xl flex items-center px-20 py-4 justify-between bg-white shadow-md">
    <div class="flex items-center text-green-800 font-semibold transition-colors duration-300 ease-in-out">
        <a href="{{ route('home') }}"
            class="hover:text-green-400">Home</a>
        <a href="{{ route('spaces.index') }}"
            class="ml-6 hover:text-green-400">Spaces</a>
    </div>

    <div class="flex items-center font-medium text-green-800 transition-colors duration-300 ease-in-out">
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