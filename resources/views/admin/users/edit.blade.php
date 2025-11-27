@extends('layouts.admin')

@section('title', 'Edit User - Sport Hub')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Edit User:</h1>

    <div class="bg-white p-6 max-w-lg">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label class="block font-semibold mb-1" for="user_name">Username</label>
                <input type="text" name="user_name" value="{{ $user->user_name }}"
                       class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1" for="email">Email</label>
                <input type="email" name="email" value="{{ $user->email }}"
                       class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1" for="phone_no">Phone</label>
                <input type="text" name="phone_no" value="{{ $user->phone_no }}"
                       class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1" for="birth_date">Birth Date</label>
                <input type="date" name="birth_date" value="{{ $user->birth_date?->format('Y-m-d') }}" 
                    class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Profile Picture</label>
                <input type="file" name="profile_pic_url"
                    class="w-full border-gray-300 rounded-xl p-3 shadow-sm bg-yellow focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1" for="is_banned">Banned</label>
                <select name="is_banned" class="w-full border p-2 rounded">
                    <option value="0" {{ !$user->is_banned ? 'selected' : '' }}>No</option>
                    <option value="1" {{ $user->is_banned ? 'selected' : '' }}>Yes</option>
                </select>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Update User
                </button>
                <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:underline">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
