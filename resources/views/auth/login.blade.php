@extends('layouts.app')
@include('auth.partials.recover')
@include('auth.partials.appeal')
@section('content')
<div class="flex justify-center items-center min-h-[90vh] py-10">

    <div class="bg-white border shadow-xl rounded-2xl p-8 w-full max-w-md">

        <h2 class="text-3xl font-bold text-center mb-4 text-emerald-800">Login</h2>

        @if (session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg mb-4 text-center text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if (session('deleted'))
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg mb-4 text-center text-sm">
                This account has been deleted.
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="text-lg">
            @csrf

            <div class="mb-4">
                <label class="block font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email"
                    class="w-full border-gray-300 rounded-xl px-4 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="example@gmail.com"
                    required>
            </div>

            <div class="mb-4">
                <label class="block font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password"
                    class="w-full border-gray-300 rounded-xl px-4 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Minimum 6 characters"
                    required>
            </div>

            <button
                class="w-full bg-emerald-800 hover:bg-emerald-200 hover:text-black text-white font-semibold px-4 py-2 my-2 rounded-xl shadow-md transition">
                Sign In
            </button>

            <p class="text-center my-2 text-gray-600">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-emerald-800 hover:underline font-medium">Create one</a>
            </p>
            <p class="text-center text-gray-600">
                Forgot your Password?
                <a onclick="openRecoverModal()" class="text-emerald-800 hover:underline font-medium">Recover it here</a>
            </p>
        </form>
    </div>

</div>
@endsection

@if ($errors->appeal->any() || session('banned'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        openAppealModal();
    });
</script>
@endif
