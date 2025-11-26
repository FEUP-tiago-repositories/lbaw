@extends('layouts.admin')

@section('title', 'Create User - Sport Hub')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Create New User</h1>

    <div class="bg-white p-6 max-w-lg rounded shadow-md">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block font-semibold mb-1" for="user_name">Username</label>
                <input type="text" name="user_name" id="user_name" 
                       class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1" for="email">Email</label>
                <input type="email" name="email" id="email" 
                       class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1" for="phone_no">Phone</label>
                <input type="text" name="phone_no" id="phone_no" 
                       class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1" for="birth_date">Birth Date</label>
                <input type="date" name="birth_date" id="birth_date" 
                       class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1" for="password">Password</label>
                <input type="password" name="password" id="password" 
                       class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1" for="is_banned">Banned</label>
                <select name="is_banned" id="is_banned" class="w-full border p-2 rounded">
                    <option value="0" selected>No</option>
                    <option value="1">Yes</option>
                </select>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Create User
                </button>
                <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:underline">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
