@extends('layouts.app')

@section('content')
    <div class="h-[85dvh] flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-xl max-w-sm w-full">
            <!-- Header -->
            <div class="p-6 border-b border-gray-200 bg-gray-600 rounded-t-3xl">
                <h2 class="text-xl font-bold text-white text-center">Confirm payment</h2>
            </div>

            <!-- Body -->
            <div class="p-8 text-center">
                <!-- Ícone de Sucesso -->
                <div class="w-24 h-24 mx-auto mb-6 rounded-full border-4 border-green-500 flex items-center justify-center">
                    <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <h3 class="text-xl font-bold text-gray-900 mb-2">Payment confirmed!</h3>
                <p class="text-lg text-gray-600 mb-6">Your reservation has been successfully confirmed.</p>

                <a href="{{ route('bookings.index', ['user_id' => auth()->id()]) }}"
                   class="text-lg inline-block px-6 py-2 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition">
                    See reservations
                </a>
            </div>
        </div>
    </div>
@endsection
