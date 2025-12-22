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

<body class="min-h-screen bg-gray-100 mx-auto">
    {{-- Navigation Bar --}}
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-2.5">
            <div class="flex justify-between py-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                        <img src="{{ asset('images/logo2.svg') }}" alt="SportsHub Logo" class="h-9 w-auto">
                    </a>
                    <div class="flex-col justify-center">
                        <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>
                        <p class="text-gray-500 text-sm">Manage the SportsHub platform</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-gray-600">{{ Auth::guard('admin')->user()->email }}</span>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors cursor-pointer">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="sticky top-[100vh] bg-emerald-800 text-white mt-auto">
        <div class="container mx-auto px-4 py-8 max-w-7xl">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <!-- Left side - Copyright -->
                <div class="text-center md:text-left">
                    <p class="opacity-90">© 2025 SportsHub. All rights reserved.</p>
                </div>

                <!-- Right side - Links -->
                <div class="flex flex-wrap justify-center md:justify-end gap-6 ">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-emerald-400">Dashboard</a>
                    <a href="{{ route('admin.users.index') }}" class="ml-6 hover:text-emerald-400">Users</a>
                    <a href="{{ route('admin.spaces.index') }}" class="ml-6 hover:text-emerald-400">Spaces</a>
                    <a href="{{ route('admin.reviews.index') }}" class="ml-6 hover:text-emerald-400">Reviews</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
