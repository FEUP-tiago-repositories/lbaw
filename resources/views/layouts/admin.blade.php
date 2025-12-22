<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin')</title>
    <title>{{ config('app.name', 'SportsHub') }}</title>
    {{-- CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
<head>

<body class="bg-gray-100">
    <nav class="navbar h-20 mx-auto text-xl flex items-center px-20 py-4 bg-white shadow-md text-emerald-800 font-semibold transition-colors duration-300 ease-in-out">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-emerald-400">Dashboard</a>
        <a href="{{ route('admin.users.index') }}" class="ml-6 hover:text-emerald-400">Users</a>
        <a href="{{ route('admin.spaces.index') }}" class="ml-6 hover:text-emerald-400">Spaces</a>
        <a href="{{ route('admin.reviews.index') }}" class="ml-6 hover:text-emerald-400">Reviews</a>
    </nav>

    <main>
        @yield('content')
    </main>
</body>
</html>
