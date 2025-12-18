<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @auth
        @if(Auth::user()->customer)
            <meta name="customer-id" content="{{ Auth::user()->customer->id }}">
            <script>console.log('Customer ID from meta:', {{ Auth::user()->customer->id }});</script>
        @else
            <script>console.error('User has no customer!');</script>
        @endif
    @endauth

    <title>@yield('title', 'Sports Hub')</title>

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    {{-- CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 mx-auto my-0">
    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')

    {{-- JavaScript --}}
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/contextual_help.js') }}" defer></script>

</body>

</html>