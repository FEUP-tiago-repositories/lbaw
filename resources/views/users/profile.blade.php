@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-12 mb-12 flex gap-6">

    <div class="flex-1 bg-white shadow-lg rounded-2xl p-10">

        {{-- Header --}}
        <div class="flex items-center gap-8">
            <img src="{{ $user->profile_pic_url ?? 'https://picsum.photos/200' }}"
                 class="w-28 h-28 rounded-full object-cover border-2 border-gray-200 shadow-sm">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">{{ $user->user_name }}</h1>
                <p class="text-gray-600 text-lg">{{ $user->email }}</p>
                <p class="text-gray-600 text-lg">Phone: {{ $user->phone_no }}</p>
            </div>
        </div>

        <hr class="my-8">

        {{-- Info --}}
        <div class="space-y-4">
            <h2 class="text-2xl font-semibold text-gray-800">Account Information</h2>

            <p class="text-lg">
                <span class="font-medium text-gray-700">Birth Date:</span>
                {{ $user->birth_date }}
            </p>

            <p class="text-lg">
                <span class="font-medium text-gray-700">Role:</span>
                @if($user->customer)
                    <span class="text-green-700 font-bold">Customer</span>
                @elseif($user->businessOwner)
                    <span class="text-blue-700 font-bold">Business Owner</span>
                @else
                    <span class="text-gray-600 font-bold">Not defined</span>
                @endif
            </p>
        </div>

        <hr class="my-8">

        {{-- Buttons --}}
        <div class="flex gap-4">
            <a href="{{ route('users.edit', $user->id) }}"
               class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-md">
                Edit Profile
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition shadow-md">
                    Log Out
                </button>
            </form>
        </div>
    </div>

    {{-- Right Column: Role-Specific Actions --}}
    <div class="w-80">
        @if($user->customer)
            {{-- Customer Actions Box --}}
            <div class="bg-green-50 border-2 border-green-200 rounded-xl p-6 shadow-lg">
                <div class="flex flex-col gap-3">
                    <a href="{{ route('bookings.index', ['user_id' => auth()->id()]) }}"
                       class="px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow text-center">
                        My Reservations                 
                    </a>
                </div>
            </div>

        @elseif($user->businessOwner)
            {{-- Business Owner Actions Box --}}
            <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6 shadow-lg">
                <div class="flex flex-col gap-3">
                    <a href="{{ route('home') }}"
                       class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow text-center">
                        Reservations Schedule
                    </a>

                    <a href="{{ route('spaces.create') }}"
                       class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow text-center">
                        Create New Space
                    </a>

                    <a href="{{ route('home') }}"
                       class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow text-center">
                        Manage Reservations
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
