@extends('layouts.admin')
@include('admin.reviews.partials.delete')
@section('title', 'Reviews Management')

@section('content')
<div class="container mx-auto px-4 py-8">

    {{-- Page Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                Reviews Management
            </h1>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="overflow-x-auto bg-white rounded-2xl shadow-md ">

        <table class="min-w-full text-center">

            {{-- Table Head --}}
            <thead class="bg-emerald-800 text-white text-sm uppercase">
                <tr>
                    <th rowspan="2" class="px-4 py-3 rounded">ID</th>
                    <th rowspan="2" class="px-4 py-3 rounded">Reviewer</th>
                    <th rowspan="2" class="px-4 py-3 rounded">Service / Space</th>
                    <th colspan="3"
                        class="px-4 py-3 rounded text-center tracking-wide">
                        Ratings
                    </th>
                    <th rowspan="2" class="px-4 py-3 rounded">Comment</th>
                    <th rowspan="2" class="px-4 py-3 rounded">Created At</th>
                    <th rowspan="2" class="px-4 py-3 rounded">Delete Review</th>
                </tr>

                <tr>
                    <th class="px-4 py-2 rounded text-xs font-medium text-gray-200">
                        Environment
                    </th>
                    <th class="px-4 py-2 rounded text-xs font-medium text-gray-200">
                        Equipment
                    </th>
                    <th class="px-4 py-2 rounded text-xs font-medium text-gray-200">
                        Service
                    </th>
                </tr>
            </thead>

            {{-- Table Body --}}
            <tbody class="divide-y divide-gray-200">
                @foreach($reviews as $review)
                    <tr class="hover:bg-gray-50 even:bg-gray-50/50 transition">

                        {{-- ID --}}
                        <td class="px-4 py-2 rounded font-medium">
                            {{ $review->id }}
                        </td>

                        {{-- Reviewer --}}
                        <td class="px-4 py-2 rounded">
                            {{ $review->customer->user->first_name ?? $review->customer->user->user_name }}
                            {{ $review->customer->user->surname ?? '' }}
                        </td>

                        {{-- Space / Service --}}
                        <td class="px-4 py-2 rounded">
                            {{ $review->booking->space->title ?? 'N/A' }}
                        </td>

                        {{-- Environment Rating --}}
                        <td class="px-4 py-2 rounded">
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                {{ $review->environment_rating >= 4
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $review->environment_rating }}/5
                            </span>
                        </td>

                        {{-- Equipment Rating --}}
                        <td class="px-4 py-2 rounded">
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                {{ $review->equipment_rating >= 4
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $review->equipment_rating }}/5
                            </span>
                        </td>

                        {{-- Service Rating --}}
                        <td class="px-4 py-2 rounded">
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                {{ $review->service_rating >= 4
                                    ? 'bg-green-100 text-green-800'
                                    : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $review->service_rating }}/5
                            </span>
                        </td>

                        {{-- Comment --}}
                        <td class="px-4 py-2 rounded text-gray-700 text-sm">
                            {{ Str::limit($review->text, 70) }}
                        </td>

                        {{-- Created At --}}
                        <td class="px-4 py-2 rounded text-sm">
                            {{ \Carbon\Carbon::parse($review->time_stamp)->format('Y-m-d') }}
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-2 rounded">
                            <button onclick="openDeleteModal({{ $review->id }})"
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