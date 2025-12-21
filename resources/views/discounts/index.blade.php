@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 max-w-6xl px-4">
    <div class="flex items-center justify-start gap-2 mb-4 text-lg">
        <a href="{{ route('home') }}" class="text-emerald-600 hover:text-emerald-400">
            <img alt="Home Page" src="/images/home-icon.svg" height="18" width="18">
        </a>
        <svg class="w-5 h-5 pt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('users.show', Auth::id()) }}" class="text-emerald-600 hover:text-emerald-400">
            Profile of {{ Auth::user()->first_name }} {{ Auth::user()->surname }}
        </a>
        <svg class="w-5 h-5 pt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <p>
            Manage Discounts
        </p>
    </div>
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manage Discounts</h1>
            <p class="text-gray-500">View and manage active promotions across all your spaces.</p>
        </div>
        <button onclick="openModal('create')" class="bg-emerald-600 text-white px-5 py-2.5 rounded-lg hover:bg-emerald-700 transition shadow-sm font-medium flex items-center gap-2">
            <span>+</span> Create Discount
        </button>
    </div>

    <div class="bg-white shadow-sm border border-gray-100 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Space</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Discount</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Period</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($discounts as $discount)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded bg-gray-200 flex items-center justify-center text-gray-500 mr-3 text-xs font-bold">
                                    IMG
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $discount->space->title }}</span>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($discount->code)
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700 border border-purple-200">
                                    {{ $discount->code }}
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                    AUTOMATIC
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-emerald-600">
                            -{{ $discount->percentage }}%
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($discount->start_date)->format('d/m/y') }} 
                            <span class="text-gray-300 mx-1">&rarr;</span> 
                            {{ \Carbon\Carbon::parse($discount->end_date)->format('d/m/y') }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button
                                onclick="openEditModal({{ $discount }})"
                                class="inline-flex items-center gap-2 rounded-lg bg-emerald-500 px-4 py-2
                                    text-white hover:bg-emerald-600 focus:outline-none focus:ring-2
                                    focus:ring-emerald-400 focus:ring-offset-1 transition">
                                    Edit
                            </button>

                            <form
                                action="{{ route('discounts.destroy', $discount->id) }}"
                                method="POST"
                                class="inline-block ml-2"
                                onsubmit="return confirm('Are you sure?');">
                                @csrf @method('DELETE')

                                <button
                                    type="submit"
                                    class="inline-flex items-center gap-2 rounded-lg bg-red-500 px-4 py-2
                                        text-white hover:bg-red-600 focus:outline-none focus:ring-2
                                        focus:ring-red-400 focus:ring-offset-1 transition">
                                    Del
                                </button>
                            </form>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                            No discounts found. Click "Create Discount" to start.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="discountModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity transition-all duration-300"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100">
                
                <div class="bg-white px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Create New Discount</h3>
                    </div>
                    
                    <button type="button" onclick="closeModal()" class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="discountForm" method="POST" action="{{ route('discounts.store') }}" class="p-6 space-y-5">
                    @csrf
                    <div id="methodField"></div> 

                    <div>
                        <label for="inputSpaceId" class="block text-sm font-medium text-gray-700 mb-1">Apply to Space</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l8-4 8 4v14"/><path d="M17 21v-8H7v8"/></svg>
                            </div>
                            <select name="space_id" id="inputSpaceId" class="block w-full rounded-lg border-gray-300 pl-10 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2.5" required>
                                <option value="">Select a space...</option>
                                @foreach($Spaces as $id => $title)
                                    <option value="{{ $id }}">{{ $title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="col-span-1 sm:col-span-2"> <label class="block text-sm font-medium text-gray-700 mb-1">
                                Discount Code 
                                <span class="text-xs text-gray-400 font-normal ml-1">(Leave empty for auto-generation)</span>
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                                </div>
                                <input type="text" name="code" id="inputCode" class="block w-full rounded-lg border-gray-300 pl-10 pr-20 uppercase placeholder-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2.5" placeholder="SUMMER25">
                                
                                <button type="button" onclick="generateCode()" class="absolute inset-y-0 right-0 flex items-center px-3 m-1 rounded-md bg-gray-50 text-gray-500 hover:text-emerald-600 hover:bg-emerald-50 text-xs font-medium border border-gray-200 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/></svg>
                                    Generate
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Percentage</label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="number" name="percentage" id="inputPercentage" min="1" max="100" required class="block w-full rounded-lg border-gray-300 pl-10 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2.5" placeholder="15">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="5" x2="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>
                                </div>
                            </div>
                        </div>

                         <div class="hidden sm:block"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="datetime-local" name="start_date" id="inputStart" required class="block w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2.5 text-gray-600">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="datetime-local" name="end_date" id="inputEnd" required class="block w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-2.5 text-gray-600">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-50">
                        <button type="button" onclick="closeModal()" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-sm transition-all hover:shadow-md flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Save Discount
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    window.DiscountConfig = {
        routes: {
            store: "{{ route('discounts.store') }}",
            update: "{{ route('discounts.update', ':id') }}"
        }
    };
</script>

<script src="{{ asset('js/discounts.js') }}"></script>
@endsection