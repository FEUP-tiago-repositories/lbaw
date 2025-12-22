@extends('layouts.admin')

@section('title', 'User Profile')

@section('content')
    <div class="max-w-3xl mx-auto mt-12 mb-12 bg-white shadow-lg rounded-2xl p-10">

        {{-- Header --}}
        <div class="relative flex items-center gap-8">
            <img src="{{ asset($user->profile_pic_url) ?? asset('images/profile.jpg') }}"
                alt="profile picture"
                class="w-28 h-28 rounded-full object-cover border-2 border-gray-200 shadow-sm">

            <div>
                <h1 class="text-4xl font-bold text-gray-900">{{ $user->user_name }}</h1>
                <p class="text-gray-600 text-lg">{{ $user->email }}</p>
                <p class="text-gray-600 text-lg">Telefone: {{ $user->phone_no }}</p>
            </div>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="absolute top-0 right-0 bg-emerald-800 font-bold text-white px-5 py-2 rounded-md text-2xl hover:bg-emerald-400"> Edit </a>
        </div>

        <hr class="my-8">

        {{-- Info --}}
        <div class="space-y-4">
            <h2 class="text-2xl font-semibold text-gray-800">Account Info</h2>

            <p class="text-lg">
                <span class="font-medium text-gray-700">Birth Date:</span>
                {{ $user->birth_date }}
            </p>

            <p class="text-lg">
                <span class="font-medium text-gray-700">Role:</span>
                @if($user->customer)
                    <span class="font-bold">Customer</span>
                @elseif($user->businessOwner)
                    <span class="font-bold">Business Owner</span>
                @else
                    <span class="text-gray-600 font-bold">Not defined</span>
                @endif
            </p>

            <p class="text-lg">
                <span class="font-medium text-gray-700">Banned Account:</span>
                @if($user->is_banned)
                    <span class="font-bold">Yes</span>
                @else
                    <span class="font-bold">No</span>
                @endif
            </p>

            <p class="text-lg">
                <span class="font-medium text-gray-700">Deleted Account:</span>
                @if($user->is_deleted)
                    <span class="font-bold">Yes</span>
                @else
                    <span class="font-bold">No</span>
                @endif
            </p>
        </div>

    </div>
@endsection