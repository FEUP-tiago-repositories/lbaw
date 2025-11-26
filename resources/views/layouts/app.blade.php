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

    {{-- CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-lime-200 mx-auto my-0 text-3xl">
    @include('partials.header')

    <main>
        @yield('content')
    </main>
    @stack('scripts')

    @include('partials.footer')

    {{-- JavaScript --}}
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>

</html>
