@extends('layouts.admin')
@include('admin.spaces.partials.delete')
@section('title', 'Spaces Management')

@section('content')
<div class="container mx-auto px-4 py-8">

    {{-- Page Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                Spaces Management
            </h1>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="overflow-x-auto bg-white rounded-2xl shadow-md">
        <table class="min-w-full text-center">

            {{-- Table Head --}}
            <thead class="bg-emerald-800 text-white text-sm uppercase">
                <tr>
                    <th class="px-4 py-3 rounded">ID</th>
                    <th class="px-4 py-3 rounded">Name</th>
                    <th class="px-4 py-3 rounded">Owner</th>
                    <th class="px-4 py-3 rounded">Sport</th>
                    <th class="px-4 py-3 rounded">Address</th>
                    <th class="px-4 py-3 rounded">Phone nº</th>
                    <th class="px-4 py-3 rounded">Email</th>
                    <th class="px-4 py-3 rounded">View Space</th>
                    <th class="px-4 py-3 rounded">Delete Space</th>
                </tr>
            </thead>

            {{-- Table Body --}}
            <tbody class="divide-y divide-gray-200">
                @foreach($spaces as $space)
                    <tr class="hover:bg-gray-50 even:bg-gray-50/50 transition">

                        {{-- ID --}}
                        <td class="px-4 py-2 rounded font-medium">
                            {{ $space->id }}
                        </td>

                        {{-- Name --}}
                        <td class="px-4 py-2 rounded">
                            {{ $space->title }}
                        </td>

                        {{-- Owner --}}
                        <td class="px-4 py-2 rounded">
                            {{ $space->owner->user->first_name ?? $space->owner->user->user_name ?? '' }}
                            {{ $space->owner->user->surname ?? '' }}
                        </td>

                        {{-- Sport --}}
                        <td class="px-4 py-2 rounded">
                            {{ $space->sportType->name ?? 'N/A' }}
                        </td>

                        {{-- Address --}}
                        <td class="px-4 py-2 rounded text-gray-700 text-sm">
                            {{ $space->address ?? 'N/A' }}
                        </td>

                        {{-- Phone --}}
                        <td class="px-4 py-2 rounded text-sm">
                            {{ $space->phone_no ?? 'N/A' }}
                        </td>

                        {{-- Email --}}
                        <td class="px-4 py-2 rounded text-sm">
                            {{ $space->email ?? 'N/A' }}
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-2 rounded">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.spaces.show', $space->id) }}"
                                   class="px-3 py-1 rounded-lg bg-emerald-100 text-emerald-700 font-semibold
                                          hover:bg-emerald-200 transition">
                                    Show
                                </a>
                        </td>
                        <td class="px-4 py-2 rounded">
                        <button onclick="openDeleteModal({{ $space->id }})"
                                    class="px-3 py-1 rounded-lg bg-red-100 text-red-700 font-semibold hover:bg-red-200 transition">
                                Delete
                            </button>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection