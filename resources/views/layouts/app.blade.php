<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Default Title')</title>
    {{-- CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-lime-200 mx-auto my-0 text-3xl">
    @include('partials.header')

    <main>
        @yield('content')
    </main>

    {{-- JavaScript --}}
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>

</html>