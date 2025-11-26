@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen py-10">

    <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-lg">

        <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Create Account</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-5">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="user_name"
                        class="w-full border-gray-300 rounded-xl p-3 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email"
                        class="w-full border-gray-300 rounded-xl p-3 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone_no"
                        class="w-full border-gray-300 rounded-xl p-3 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">Birth Date</label>
                    <input type="date" name="birth_date"
                        class="w-full border-gray-300 rounded-xl p-3 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

            </div>

            <div class="mt-4">
                <label class="block font-medium text-gray-700 mb-2">Account Type</label>

                <div class="flex items-center gap-6">

                    <label class="flex items-center gap-2">
                        <input type="radio" name="account_type" value="customer" required>
                        <span>Customer</span>
                    </label>

                    <label class="flex items-center gap-2">
                        <input type="radio" name="account_type" value="business" required>
                        <span>Business Owner</span>
                    </label>

                </div>
            </div>


            <div class="mt-4">
                <label class="block font-medium text-gray-700 mb-1">Profile Picture (optional)</label>
                <input type="file" name="profile_pic_url"
                    class="w-full border-gray-300 rounded-xl p-3 shadow-sm bg-yellow focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mt-4">
                <label class="block font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password"
                    class="w-full border-gray-300 rounded-xl p-3 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    required>
            </div>

            <div class="mt-4">
                <label class="block font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full border-gray-300 rounded-xl p-3 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    required>
            </div>

            <button
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold p-3 rounded-xl shadow-md mt-6 transition">
                Create Account
            </button>

            <p class="text-center mt-4 text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Sign in</a>
            </p>

        </form>
    </div>
</div>
@endsection
