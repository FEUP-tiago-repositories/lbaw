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
@section('content')
<div class="fixed inset-0 flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-96 text-center text-xl">

        <h2 class="text-2xl font-bold text-gray-800 mb-4">Reset Your Password</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('status'))
            <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <input type="password" name="password" placeholder="New password"
                   required
                   class="w-full border-gray-300 rounded-xl p-3 shadow-sm mb-4">

            <input type="password" name="password_confirmation" placeholder="Confirm new password"
                   required
                   class="w-full border-gray-300 rounded-xl p-3 shadow-sm mb-4">

            <button type="submit"
                    class="px-6 py-3 text-lg bg-emerald-800 text-white rounded-lg hover:bg-emerald-200 hover:text-black transition shadow text-center font-medium w-full">
                Reset Password
            </button>
        </form>
    </div>
</div>
