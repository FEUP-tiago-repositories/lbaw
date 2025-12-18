@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto my-8">
    <div class="flex items-center ml-4 gap-2 mb-6 text-lg">
        <a href="{{ route('home') }}" class="text-emerald-600 hover:text-emerald-400">
            <img alt="Home Page" src="/images/home-icon.svg" height="18" width="18">
        </a>
        <svg class="w-5 h-5 pt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('users.show', Auth::id()) }}" class="text-emerald-600 hover:text-emerald-400">
            Profile of {{ Auth::user()->user_name }}
        </a>
        <svg class="w-5 h-5 pt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <p>
            Edit Profile
        </p>
    </div>
    <div class="bg-white shadow-lg rounded-2xl p-10">
        <div class = "flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit Profile</h1>

            <button type="button" onclick="toggleModal()"
                    class="w-12 h-12 rounded-full bg-emerald-700 text-white font-bold flex items-center justify-center hover:bg-emerald-500 transition shadow-lg">
                ?
            </button>
        </div>
        <div id="helpModal" class="fixed inset-0 bg-transparent bg-opacity-60 flex items-center justify-center z-50 hidden backdrop-blur-sm transition-opacity duration-300">

            <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-3xl w-full min-h-[500px] relative transform transition-all scale-100 mx-4">
                <div class="text-gray-600 text-center mb-8 leading-relaxed">
                    @include('partials.help.edit_user')
                </div>

                <div class="flex justify-center">
                    <button onclick="toggleModal()" class="px-8 py-3 bg-red-600 text-white rounded-full font-semibold shadow-lg hover:bg-red-700 hover:shadow-xl transition transform hover:-translate-y-0.5">
                        Close
                    </button>
                </div>
            </div>
        </div>
        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')
            {{-- Username --}}
            <div>
                <label class="block text-gray-700 font-medium mb-1">Username</label>
                <input type="text" name="user_name" value="{{ old('user_name', $user->user_name) }}"
                    required minlength="3" maxlength="20"
                    pattern="[A-Za-z0-9_]+"
                    title="Username can only contain letters, numbers, and underscores."
                    class="w-full border-gray-300 rounded-xl p-3 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                    required maxlength="255"
                    class="w-full border-gray-300 rounded-xl p-3 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Phone --}}
            <div>
                <label class="block text-gray-700 font-medium mb-1">Phone</label>
                <input type="text" name="phone_no" value="{{ old('phone_no', $user->phone_no) }}"
                    required pattern="[0-9]{9}" maxlength="9"
                    title="Phone number must be exactly 9 digits."
                    class="w-full border-gray-300 rounded-xl p-3 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Birth Date --}}
            <div>
                <label class="block text-gray-700 font-medium mb-1">Birth Date</label>
                <input type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date) }}"
                    max="{{ date('Y-m-d', strtotime('-18 years')) }}"
                    title="You must be at least 18 years old."
                    required
                    class="w-full border-gray-300 rounded-xl p-3 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Profile Picture --}}
            <div>
                <label class="block text-gray-700 font-medium mb-1">Profile Picture</label>
                <div class="flex items-center gap-4">
                    <img src="{{ $user->profile_pic_url ?? 'https://via.placeholder.com/120' }}"
                         class="w-20 h-20 rounded-full object-cover border shadow">
                    <input type="file" name="profile_pic_url"
                           class="block w-full text-gray-700">
                </div>
            </div>
            <hr class="my-6">
            {{-- Save Button --}}
            <div class="flex justify-end gap-4">
                <a href="{{ route('users.show', $user->id) }}"
                   class="px-5 py-3 bg-emerald-200 text-black rounded-xl hover:bg-emerald-100 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-emerald-800 text-white hover:bg-emerald-200 hover:text-black rounded-xl hover:bg-blue-700 transition shadow-md">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection