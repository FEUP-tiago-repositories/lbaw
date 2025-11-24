<nav class="navbar h-16 max-w-7xl mx-auto flex items-center px-8 pt-8 pb-4 justify-between bg-white shadow-md">
    <div>
        <a href="{{ route('home') }}"
            class="text-lg font-medium hover:text-blue-500 transition-colors duration-300 ease-in-out">Home</a>
        <a href="{{ route('spaces.index') }}"
            class="ml-6 text-lg font-medium hover:text-blue-500 transition-colors duration-300 ease-in-out">Spaces</a>
    </div>

    <div>
        @auth {{-- -If user is authenticated --}}
            <a href="{{ route('logout') }}"
                class="text-lg font-medium hover:text-blue-500 transition-colors duration-300 ease-in-out"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @else
            <a href="{{ route('login') }}"
                class="text-lg font-medium hover:text-blue-500 transition-colors duration-300 ease-in-out">Login</a>
            <a href="{{ route('register') }}"
                class="ml-6 text-lg font-medium hover:text-blue-500 transition-colors duration-300 ease-in-out">Register</a>
        @endauth
    </div>
</nav>