<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'SportsHub') }}</title>

    {{-- CSS --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/app.css')
</head>

<body>
    <nav class="navbar">
        {{-- Navegação --}}
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('spaces.index') }}">Espaços</a>

    </nav>

    <main>
        @yield('content')
    </main>

    {{-- JavaScript --}}
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>

</html>