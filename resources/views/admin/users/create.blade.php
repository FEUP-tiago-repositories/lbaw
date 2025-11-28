@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
<div class="flex justify-center items-center min-h-screen py-10">

    <div class="bg-white p-8 w-full max-w-lg rounded shadow-xl rounded-2xl">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-2 mb-4 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <h1 class="text-2xl font-bold mb-6">Create New User</h1>

            <div class="mb-4">
                <label class="block font-semibold mb-1" for="user_name">Username</label>
                <input type="text" name="user_name"
                       class="w-full border-gray-300 p-3 rounded-xl shadow-sm" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1" for="email">Email</label>
                <input type="email" name="email"
                       class="w-full border-gray-300 p-3 rounded-xl shadow-sm" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1" for="phone_no">Phone</label>
                <input type="text" name="phone_no"
                       class="w-full border-gray-300 p-3 rounded-xl shadow-sm">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1" for="birth_date">Birth Date</label>
                <input type="date" name="birth_date"
                       class="w-full border-gray-300 p-3 rounded-xl shadow-sm">
            </div>


            <div class="mb-4">
                <label class="block font-semibold mb-1">Account Type</label>

                <div class="flex items-center gap-6">

                    <label class="flex items-center gap-2">
                        <input type="radio" name="account_type" value="customer" required {{ old('account_type') == 'customer' ? 'checked' : '' }}>
                        <span>Customer</span>
                    </label>

                    <label class="flex items-center gap-2">
                        <input type="radio" name="account_type" value="business_owner" required {{ old('account_type') == 'business_owner' ? 'checked' : '' }}>
                        <span>Business Owner</span>
                    </label>

                </div>
                @error('account_type')
                    <span class="text-red-500 text-sm mb-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Profile Picture (optional)</label>
                <input type="file" name="profile_pic_url"
                    class="w-full border-gray-300 rounded-xl p-3 shadow-sm bg-yellow focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1" for="password">Password</label>
                <input type="password" name="password"
                       class="w-full border-gray-300 p-3 rounded-xl shadow-sm" required>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="bg-emerald-800 font-bold text-white px-5 py-2 rounded-md hover:bg-emerald-400">
                    Create User
                </button>
                <a href="{{ route('admin.users.index') }}" class="bg-red-700 font-bold text-white px-5 py-2 rounded-md hover:bg-red-500">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
