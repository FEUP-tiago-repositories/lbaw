@extends('layouts.admin') 

@section('title', 'User Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Users</h1>
        <a href="{{ route('admin.users.create') }}" class="bg-emerald-800 font-bold text-white py-2 px-5 rounded-md hover:bg-emerald-400">
            Add New User
        </a>
    </div>

    <div class="overflow-x-auto w-full">
        <table class="min-w-full bg-white border shadow-sm rounded text-center">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Username</th>
                    <th class="px-4 py-2 border">Birth</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Phone</th>
                    <th class="px-4 py-2 border">Account Type</th>
                    <th class="px-4 py-2 border">Banned</th>
                    <th class="px-4 py-2 border">Show User </th>
                    <th class="px-4 py-2 border">Delete User</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border"><p class="font-medium">{{ $user->id }}</p></td>
                        <td class="px-4 py-2 border"><p class="font-medium">{{ $user->user_name }}</p></td>
                        <td class="px-4 py-2 border"><p class="font-medium">{{ $user->birth_date}}</p></td>
                        <td class="px-4 py-2 border"><p class="font-medium">{{ $user->email }}</p></td>
                        <td class="px-4 py-2 border"><p class="font-medium">{{ $user->phone_no }}</p></td>
                        <td class="px-4 py-2 border">
                            @if(optional($user->customer)->id)
                                <p class="font-medium">Customer</sppan>

                            @elseif(optional($user->businessOwner)->id)
                                <p class="font-medium">Business Owner</p>

                            @else
                                <p class="font-medium">Not defined</p>
                            @endif
                        </td>

                        <td class="px-4 py-2 border">
                            @if($user->is_banned)
                                <p class="font-medium"> Yes </p>
                            @else
                                <p class="font-medium"> No </p>
                            @endif
                        </td>
                        <td class="px-4 py-2 border">
                            <a href="{{ route('admin.users.show', $user->id) }}" 
                            class="hover:text-emerald-400 text-emerald-800 font-bold"> Show </a>
                        </td>
                        <td class="px-4 py-2 border">
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this user?');"> 
                                @csrf @method('DELETE') 
                                <button class="hover:text-red-400 text-red-700 font-bold">Delete</button> 
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
