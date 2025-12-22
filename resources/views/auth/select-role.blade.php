@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-[85vh] py-10">
    <div class="bg-white border shadow-xl rounded-2xl p-8 w-full max-w-md">
        <div class="text-center mb-6">
            @if(session('oauth_avatar'))
                <img src="{{ session('oauth_avatar') }}" 
                     alt="Profile" 
                     class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-emerald-800">
            @endif
            <h2 class="text-3xl font-bold text-emerald-800">Welcome, {{ session('oauth_name') }}!</h2>
            <p class="text-gray-600 mt-2">Choose the type of account to continue</p>
        </div>

        <form action="{{ route('oauth.complete') }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-4">
                <label class="block">
                    <input type="radio" name="role" value="customer" required class="mr-3">
                    <span class="inline-flex items-center">
                        <svg class="w-6 h-6 mr-2 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <div class="text-left">
                            <div class="font-semibold text-lg">Customer</div>
                            <div class="text-sm text-gray-600">Booking Sport Spaces</div>
                        </div>
                    </span>
                </label>

                <label class="block">
                    <input type="radio" name="role" value="business_owner" required class="mr-3">
                    <span class="inline-flex items-center">
                        <svg class="w-6 h-6 mr-2 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <div class="text-left">
                            <div class="font-semibold text-lg">Business Owner</div>
                            <div class="text-sm text-gray-600">Manage Sport Spaces</div>
                        </div>
                    </span>
                </label>
            </div>

            @error('role')
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm">
                    {{ $message }}
                </div>
            @enderror

            <button type="submit" 
                    class="w-full bg-emerald-800 hover:bg-emerald-200 hover:text-black text-white font-semibold px-4 py-3 rounded-xl shadow-md transition">
                Continuar
            </button>
        </form>
    </div>
</div>
@endsection
