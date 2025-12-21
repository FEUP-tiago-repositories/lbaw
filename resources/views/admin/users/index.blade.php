@extends('layouts.admin') 
@include('admin.users.partials.ban')
@include('admin.users.partials.unban')
@include('admin.users.partials.delete')
@section('title', 'User Management')

@section('content')
<div class="container mx-auto px-4 py-8">

    {{-- Page Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                User Management
            </h1>
        </div>
        <a href="{{ route('admin.users.create') }}" 
           class="bg-emerald-800 font-bold text-white py-2 px-5 rounded-md hover:bg-emerald-700 transition">
            Add New User
        </a>
    </div>

    {{-- Table Card --}}
    <div class="overflow-x-auto bg-white rounded-2xl shadow-md">
        <table class="min-w-full text-center">

            {{-- Table Head --}}
            <thead class="bg-emerald-800 text-white text-sm uppercase">
                <tr>
                    <th class="px-4 py-3 rounded">ID</th>
                    <th class="px-4 py-3 rounded">Username</th>
                    <th class="px-4 py-3 rounded">Birth</th>
                    <th class="px-4 py-3 rounded">Email</th>
                    <th class="px-4 py-3 rounded">Phone</th>
                    <th class="px-4 py-3 rounded">Account Type</th>
                    <th class="px-4 py-3 rounded">Banned</th>
                    <th class="px-4 py-3 rounded">Show User</th>
                    <th class="px-4 py-3 rounded">Delete User</th>
                    <th class="px-4 py-3 rounded">Ban User</th>
                </tr>
            </thead>

            {{-- Table Body --}}
            <tbody class="divide-y divide-gray-200">
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50 even:bg-gray-50/50 transition">

                        {{-- ID --}}
                        <td class="px-4 py-2 rounded font-medium">
                            {{ $user->id }}
                        </td>

                        {{-- Username --}}
                        <td class="px-4 py-2 rounded">
                            {{ $user->user_name }}
                        </td>

                        {{-- Birth Date --}}
                        <td class="px-4 py-2 rounded text-sm">
                            {{ $user->birth_date }}
                        </td>

                        {{-- Email --}}
                        <td class="px-4 py-2 rounded text-sm">
                            {{ $user->email }}
                        </td>

                        {{-- Phone --}}
                        <td class="px-4 py-2 rounded text-sm">
                            {{ $user->phone_no }}
                        </td>

                        {{-- Account Type --}}
                        <td class="px-4 py-2 rounded">
                            @if(optional($user->customer)->id)
                                <p class="font-medium">Customer</sppan>

                            @elseif(optional($user->businessOwner)->id)
                                <p class="font-medium">Business Owner</p>

                            @else
                                <p class="font-medium">Not defined</p>
                            @endif
                        </td>

                        <td class="px-4 py-2 rounded">
                            @if($user->is_banned)
                                <p class="font-medium"> Yes </p>
                            @else
                                <p class="font-medium"> No </p>
                            @endif
                        </td>

                        <td class="px-4 py-2 rounded">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                   class="px-3 py-1 rounded-lg bg-emerald-100 text-emerald-700 font-semibold
                                          hover:bg-emerald-200 transition">
                                    Show
                                </a>
                            </div>
                        </td>

                        <td class="px-4 py-2 rounded">
                        <button onclick="openDeleteModal({{ $user->id }})"
                                    class="px-3 py-1 rounded-lg bg-red-100 text-red-700 font-semibold hover:bg-red-200 transition">
                                Delete
                            </button>
                        </td>
                                    <td class="px-4 py-2">
                            @if ($user->is_banned)
                                <button
                                    type="button"
                                    onclick="openUnbanModal({{ $user->id }}, '{{ $user->ban->motive ?? 'Not Available' }}','{{ $user->ban->ban_appeal->appeal ?? 'Not Available' }}')"

                                    class="px-3 py-1 rounded-lg bg-blue-100 text-blue-700 font-semibold
                                        hover:bg-blue-200 transition">
                                    Unban
                                </button>
                            @else
                                <button
                                    type="button"
                                    onclick="openBanModal({{ $user->id }})"
                                    class="px-3 py-1 rounded-lg bg-orange-100 text-orange-700 font-semibold
                                        hover:bg-orange-200 transition">
                                    Ban
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
