@extends('layouts.app')

@section('content')
    <div class="flex justify-center items-center min-h-screen py-10">
        <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-lg">
            <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Create Account</h2>
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-5 text-sm">
                    <ul class="list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- First Name --}}
                        <div>
                            <label class="block font-medium text-gray-700 mb-1">First Name</label>
                            <input type="text" name="first_name"
                                value="{{ old('first_name') }}"
                                required minlength="2" maxlength="15"
                                pattern="[A-Za-z]+"
                                title="First name can only contain letters."
                                class="w-full border-gray-300 rounded-xl px-4 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="John">
                        </div>
                    {{-- Surname --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Surname</label>
                        <input type="text" name="surname"
                            value="{{ old('surname') }}"
                            required minlength="2" maxlength="15"
                            pattern="[A-Za-z]+"
                            title="Surname can only contain letters."
                            class="w-full border-gray-300 rounded-xl px-4 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Doe">
                    </div>
                    {{-- Username --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" name="user_name"
                            value="{{ old('user_name') }}"
                            required minlength="3" maxlength="20"
                            pattern="[A-Za-z0-9_]+"
                            title="Username can only contain letters, numbers, and underscores."
                            class="w-full border-gray-300 rounded-xl px-4 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="User123">
                    </div>
                    {{-- Email --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email"
                            value="{{ old('email') }}"
                            required maxlength="255"
                            class="w-full border-gray-300 rounded-xl px-4 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="example@gmail.com">
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="text" name="phone_no"
                            value="{{ old('phone_number') }}"
                            required pattern="[0-9]{9}" maxlength="9"
                            title="Phone number must be exactly 9 digits."
                            class="w-full border-gray-300 rounded-xl px-4 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="936548954">
                    </div>

                    {{-- Birth Date --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Birth Date</label>
                        <input type="date" name="birth_date"
                            value="{{ old('birth_date') }}"
                            required
                            max="{{ \Carbon\Carbon::now()->subYears(18)->format('Y-m-d') }}"
                            title="You must be at least 18 years old."
                            class="w-full border-gray-300 rounded-xl px-4 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                {{-- Account Type --}}
                <div class="mt-4">
                    <label class="block font-medium text-gray-700 mb-2">Account Type</label>

                    <div class="flex items-center gap-6">

                        <label class="inline-flex items-center gap-4 py-2">
                            <input type="radio" name="role" value="customer" required {{ old('role') == 'customer' ? 'checked' : '' }}>
                            <span>Customer</span>
                        </label>

                        <label class="inline-flex items-center gap-4 py-2">
                            <input type="radio" name="role" value="business_owner" required {{ old('role') == 'business_owner' ? 'checked' : '' }}>
                            <span>Business Owner</span>
                        </label>

                    </div>
                    @error('role')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>


                <div class="mt-4">
                    <label class="block font-medium text-gray-700 mb-1">Profile Picture (optional)</label>
                    <input type="file" name="profile_pic_url"
                        class="w-full border-gray-300 rounded-xl px-4 py-2 shadow-sm bg-yellow focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Password --}}
                <div class="mt-4">
                    <label class="block font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password"
                        required minlength="6"
                        class="w-full border-gray-300 rounded-xl px-4 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Minimum 6 characters"
                        required>
                </div>

                {{-- Confirm Password --}}
                <div class="mt-4">
                    <label class="block font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                        required minlength="6"
                        class="w-full border-gray-300 rounded-xl px-4 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Confirm your password">
                </div>

                <button
                    class="w-full bg-emerald-800 hover:bg-emerald-200 text-white hover:text-black font-semibold px-4 py-2 rounded-xl shadow-md mt-6 transition">
                    Create Account
                </button>

                <p class="text-center mt-4 text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-emerald-800 hover:underline font-medium">Sign in</a>
                </p>

            </form>
        </div>
    </div>
@endsection