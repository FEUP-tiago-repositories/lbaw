@extends('layouts.admin') 

@section('title', 'User Management - Sport Hub')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Users</h1>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Add New User
        </a>
    </div>

    <div class="overflow-x-auto w-full">
        <table class="min-w-full bg-white border border-gray-200 rounded text-center">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Username</th>
                    <th class="px-4 py-2 border">Birth</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Phone</th>
                    <th class="px-4 py-2 border">Banned</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border">{{ $user->id }}</td>
                        <td class="px-4 py-2 border">{{ $user->user_name }}</td>
                        <td class="px-4 py-2 border">{{ $user->birth_date}}</td>
                        <td class="px-4 py-2 border">{{ $user->email }}</td>
                        <td class="px-4 py-2 border">{{ $user->phone_no }}</td>
                        <td class="px-4 py-2 border">
                            @if($user->is_banned)
                                <p class="font-semibold"> Yes </p>
                            @else
                                <p class="font-semibold"> No </p>
                            @endif
                        </td>
                        <td class="px-4 py-2 border">
                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                            class="text-blue-600 hover:underline"> Edit </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
