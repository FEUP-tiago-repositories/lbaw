@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto mt-12 mb-12 bg-white shadow-lg rounded-2xl p-10">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit Profile</h1>
    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6" text-xl>
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
@endsection