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

        <div class="flex justify-center items-center min-h-screen py-10">

            <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-md border-2 border-gray-400">

                <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Admin Login</h2>

                @if (session('error'))
                    <div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg mb-4 text-center text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg mb-4 text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.login.submit') }}" method="POST" class="text-xl">
                    @csrf

                    <div class="mb-5">
                        <label class="block font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full border-gray-300 rounded-xl p-3 shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                            placeholder="admin.example@gmail.com" required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password"
                            class="w-full border-gray-300 rounded-xl p-3 shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                            placeholder="password" required>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        class="w-full bg-emerald-800 hover:bg-emerald-200 hover:text-black text-white font-semibold p-3 rounded-xl shadow-md transition">
                        Sign In
                    </button>
                </form>
            </div>

        </div>