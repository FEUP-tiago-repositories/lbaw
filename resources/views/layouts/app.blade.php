<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:image" content="{{ asset('images/logo.svg') }}">
    <meta property="og:image:alt" content="Sports Hub Logo">

    @auth
        <meta name="user-id" content="{{ Auth::id() }}">
        @if(Auth::user()->customer)
            <meta name="customer-id" content="{{ Auth::user()->customer->id }}">
            <script>console.log('User ID:', {{ Auth::id() }}, 'Customer ID:', {{ Auth::user()->customer->id }});</script>
        @endif
    @endauth

    <title>@yield('title', 'Sports Hub')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    {{--
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}"> --}}
    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="{{ asset('js/search.js') }}"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>

<body class="min-h-screen bg-gray-100 mx-auto my-0">
    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')

    {{-- JavaScript --}}
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('js/contextual_help.js') }}" defer></script>
    <script src="{{ asset('js/notifications.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>