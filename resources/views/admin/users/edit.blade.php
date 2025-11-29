@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="flex justify-center items-center min-h-screen py-10">

    <div class="bg-white p-8 w-full max-w-lg rounded shadow-xl rounded-2xl">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PATCH')

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-2 mb-4 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h1 class="text-2xl font-bold mb-6">Edit User</h1>

            <div class="mb-4">
                <label class="block font-semibold mb-1" for="user_name">Username</label>
                <input type="text" name="user_name" value="{{ $user->user_name }}"
                       class="w-full border-gray-300 p-3 rounded-xl shadow-sm" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1" for="email">Email</label>
                <input type="email" name="email" value="{{ $user->email }}"
                       class="w-full border-gray-300 p-3 rounded-xl shadow-sm"required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1" for="phone_no">Phone</label>
                <input type="text" name="phone_no" value="{{ $user->phone_no }}"
                       class="w-full border-gray-300 p-3 rounded-xl shadow-sm">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1" for="birth_date">Birth Date</label>
                <input type="date" name="birth_date" value="{{ $user->birth_date}}" 
                    class="w-full border-gray-300 p-3 rounded-xl shadow-sm">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Profile Picture</label>
                <input type="file" name="profile_pic_url"
                    class="w-full border-gray-300 rounded-xl p-3 shadow-sm bg-yellow focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1" for="is_banned">Banned</label>
                <select name="is_banned" class="w-full border-gray-300 p-3 rounded-xl shadow-sm">
                    <option value="0" {{ !$user->is_banned ? 'selected' : '' }}>No</option>
                    <option value="1" {{ $user->is_banned ? 'selected' : '' }}>Yes</option>
                </select>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="bg-emerald-800 font-bold text-white px-5 py-2 rounded-md hover:bg-emerald-400">
                    Update User
                </button>
                <a href="{{ route('admin.users.index') }}" class="bg-red-700 font-bold text-white px-5 py-2 rounded-md hover:bg-red-500">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
