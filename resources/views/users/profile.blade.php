@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-12 mb-12 bg-white shadow-lg rounded-2xl p-10">

    {{-- Header --}}
    <div class="flex items-center gap-8">
        <img
            src="{{ $user->profile_pic_url ?? 'https://via.placeholder.com/120' }}"
            class="w-28 h-28 rounded-full object-cover border-2 border-gray-200 shadow-sm"
        >

        <div>
            <h1 class="text-4xl font-bold text-gray-900">{{ $user->user_name }}</h1>
            <p class="text-gray-600 text-lg">{{ $user->email }}</p>
            <p class="text-gray-600 text-lg">Telefone: {{ $user->phone_no }}</p>
        </div>
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
            @if(\DB::table('customer')->where('user_id', $user->id)->exists())
                <span class="text-green-700 font-bold">Cliente</span>
            @elseif(\DB::table('business_owner')->where('user_id', $user->id)->exists())
                <span class="text-blue-700 font-bold">Business Owner</span>
            @else
                <span class="text-gray-600 font-bold">Not defined</span>
            @endif
        </p>

        <p class="text-lg">
            <span class="font-medium text-gray-700">Banned Account:</span>
            @if($user->is_banned)
                <span class="text-red-600 font-bold">Yes</span>
            @else
                <span class="text-green-700 font-bold">No</span>
            @endif
        </p>

        <p class="text-lg">
            <span class="font-medium text-gray-700">Deleted Account:</span>
            @if($user->is_deleted)
                <span class="text-red-600 font-bold">Yes</span>
            @else
                <span class="text-green-700 font-bold">No</span>
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
            <button
                class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition shadow-md">
                Log Out
            </button>
        </form>
    </div>

</div>
@endsection
