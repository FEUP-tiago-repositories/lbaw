<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
@section('content')
<div class="fixed inset-0 flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-96 text-center text-xl">

        <h2 class="text-2xl font-bold text-gray-800 mb-4">Reset Your Password</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('status'))
            <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <label for="password" class="block text-gray-700 text-sm font-medium mb-1">New Password</label>
            <input type="password" name="password" placeholder="New password" is="password"
                   required
                   class="w-full border-gray-300 rounded-xl p-3 shadow-sm mb-4">
            <label for="password_confirmation" class="block text-gray-700 text-sm font-medium mb-1">Confirm New Password</label>
            <input type="password" name="password_confirmation" placeholder="Confirm new password" id="password_confirmation"
                   required
                   class="w-full border-gray-300 rounded-xl p-3 shadow-sm mb-4">

            <button type="submit"
                    class="px-6 py-3 text-lg bg-emerald-800 text-white rounded-lg hover:bg-emerald-200 hover:text-black transition shadow text-center font-medium w-full">
                Reset Password
            </button>
        </form>
    </div>
</div>
