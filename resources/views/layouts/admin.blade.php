<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin')</title>
    <title>{{ config('app.name', 'SportsHub') }}</title>
    {{-- CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
<head>

<body class="bg-gray-100">
    <nav class="bg-gray-800 text-white p-4 flex justify-center w-full">
        <a href="{{ route('admin.dashboard') }}" class="mr-4">Dashboard</a>
        <a href="{{ route('admin.users.index') }}" class="mr-4">Users</a>
        <a href="{{ route('admin.spaces.index') }}">Spaces</a>
    </nav>

    <main>
        @yield('content')
    </main>
</body>
</html>
