@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen py-10">

    <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-md">

        <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Login</h2>

        @if (session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg mb-4 text-center">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label class="block font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email"
                    class="w-full border-gray-300 rounded-xl p-3 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    required>
            </div>

            <div class="mb-6">
                <label class="block font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password"
                    class="w-full border-gray-300 rounded-xl p-3 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    required>
            </div>

            <button
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold p-3 rounded-xl shadow-md transition">
                Sign In
            </button>

            <p class="text-center mt-4 text-gray-600">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-medium">Create one</a>
            </p>
        </form>
    </div>

</div>
@endsection
