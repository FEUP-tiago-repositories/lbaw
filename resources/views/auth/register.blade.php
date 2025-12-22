@extends('layouts.app')

@section('content')
    <div class="flex justify-center items-center min-h-[85vh] py-10">
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

            @if (session('error'))
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg mb-4 text-center text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="">
                @csrf
                {{-- OAuth Section --}}
                <div class="flex gap-3 mb-4">
                    {{-- Google OAuth Button --}}
                    <a href="{{ route('google-auth') }}"
                       class="flex-1 flex items-center justify-center gap-2 px-3 py-2.5 border border-gray-300 rounded-xl bg-white hover:bg-gray-50 hover:border-blue-500 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-300 group">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span class="font-medium text-sm text-gray-700 group-hover:text-blue-600 transition-colors">Google</span>
                    </a>

                    {{-- Facebook OAuth Button --}}
                    <a href="{{ route('facebook-auth') }}"
                       class="flex-1 flex items-center justify-center gap-2 px-3 py-2.5 border border-gray-300 rounded-xl bg-white hover:bg-gray-50 hover:border-blue-600 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-300 group">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#0081FB" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        <span class="font-medium text-sm text-gray-700 group-hover:text-blue-600 transition-colors">Facebook</span>
                    </a>
                </div>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">or continue with</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- First Name --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
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
                        <label class="block font-medium text-gray-700 mb-1">Surname <span class="text-red-500">*</span></label>
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
                        <label class="block font-medium text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
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
                        <label class="block font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email"
                               value="{{ old('email') }}"
                               required maxlength="255"
                               class="w-full border-gray-300 rounded-xl px-4 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="example@gmail.com">
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                        <input type="text" name="phone_no"
                               value="{{ old('phone_number') }}"
                               required pattern="[0-9]{9}" maxlength="9"
                               title="Phone number must be exactly 9 digits."
                               class="w-full border-gray-300 rounded-xl px-4 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="936548954">
                    </div>

                    {{-- Birth Date --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Birth Date <span class="text-red-500">*</span></label>
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
                    <label class="block font-medium text-gray-700 mb-2">Account Type <span class="text-red-500">*</span></label>
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
                    <label class="block font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password" id="register-password"
                               required minlength="6"
                               class="w-full border-gray-300 rounded-xl px-4 py-2 pr-12 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Minimum 6 characters">
                        <button type="button" onclick="togglePasswordVisibility('register-password', this)"
                                class="absolute right-0 top-0 h-full px-4 text-gray-400 hover:text-emerald-800 transition-colors">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div class="mt-4">
                    <label class="block font-medium text-gray-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="register-password-confirm"
                               required minlength="6"
                               class="w-full border-gray-300 rounded-xl px-4 py-2 pr-12 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Confirm your password">
                        <button type="button" onclick="togglePasswordVisibility('register-password-confirm', this)"
                                class="absolute right-0 top-0 h-full px-4 text-gray-400 hover:text-emerald-800 transition-colors">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
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

    {{-- Include OAuth JavaScript --}}
    <script src="{{ asset('js/oauth.js') }}"></script>
@endsection
