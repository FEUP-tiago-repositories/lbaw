<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SportsHub') }}</title>

    {{-- CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-lime-200 mx-auto my-0 text-3xl">
    <nav class="navbar h-[4rem] max-w-7xl flex items-center px-8 pt-8 pb-2 justify-between">
        {{-- Navegação --}}
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('spaces.index') }}">Espaços</a>

        @auth
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Registar</a>
        @endauth
    </nav>

    <main>
        @yield('content')
    </main>

    {{-- JavaScript --}}
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>

</html>
