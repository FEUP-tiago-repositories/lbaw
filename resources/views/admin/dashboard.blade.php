@extends('layouts.admin')
@section('title', 'Dashboard Admin')

@section('content')
    <body class="bg-gray-100">
       {{-- Main Content --}}
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{-- Welcome Message --}}
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Welcome to the Admin Panel</h2>
                <p class="text-gray-600">Manage users, spaces, and reviews quickly from this dashboard.</p>
            </div>

            {{-- Quick Actions Section --}}
            <h3 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h3>

            {{-- Dashboard Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                {{-- Users Management --}}
                <a href="{{ route('admin.users.index') }}"
                    class="bg-white rounded-xl shadow-sm p-6 group border border-gray-300 hover:border-2 hover:border-emerald-700 transition-colors">
                    <div class="bg-green-50 w-12 h-12 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Users Management</h4>
                    <p class="text-gray-500 text-sm mb-4">View, edit, ban/unban users</p>
                    <span class="text-green-600 text-sm font-medium group-hover:underline">Manage →</span>
                </a>

                {{-- Spaces Management --}}
                <a href="{{ route('admin.spaces.index') }}"
                    class="bg-white rounded-xl shadow-sm p-6 group border border-gray-300 hover:border-2 hover:border-emerald-700 transition-colors">
                    <div class="bg-green-50 w-12 h-12 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Spaces Management</h4>
                    <p class="text-gray-500 text-sm mb-4">View and delete sport spaces</p>
                    <span class="text-green-600 text-sm font-medium group-hover:underline">Manage →</span>
                </a>

                {{-- Reviews Management --}}
                <a href="{{ route('admin.reviews.index') }}"
                    class="bg-white rounded-xl shadow-sm p-6 group border border-gray-300 hover:border-2 hover:border-emerald-700 transition-colors">
                    <div class="bg-green-50 w-12 h-12 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Reviews Management</h4>
                    <p class="text-gray-500 text-sm mb-4">View and delete reviews</p>
                    <span class="text-green-600 text-sm font-medium group-hover:underline">Manage →</span>
                </a>

            </div>

            {{-- Quick Statistics --}}
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Quick Statistics</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <p class="text-3xl font-bold text-green-600">{{ \DB::table('user')->count() }}</p>
                        <p class="text-gray-600">Total Users</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-green-600">{{ \DB::table('space')->count() }}</p>
                        <p class="text-gray-600">Total Spaces</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-green-600">{{ \DB::table('review')->count() }}</p>
                        <p class="text-gray-600">Total Reviews</p>
                    </div>
                </div>
            </div>

        </div>

    </body>
@endsection