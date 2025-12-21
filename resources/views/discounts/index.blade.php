@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 max-w-6xl px-4">
    
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

<div id="discountModal" class="fixed inset-0 bg-transparent bg-opacity-50 hidden z-50 flex items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
        
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900" id="modalTitle">Create New Discount</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
        </div>

        <div class="p-6">
            <form id="discountForm" method="POST" action="{{ route('discounts.store') }}">
                @csrf
                <div id="methodField"></div> <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Apply to Space</label>
                    <select name="space_id" id="inputSpaceId" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                        <option value="">Select a space...</option>
                        @foreach($Spaces as $id => $title)
                            <option value="{{ $id }}">{{ $title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Code <span class="text-xs text-gray-400 font-normal">(Leave empty for auto)</span></label>
                    <div class="flex gap-2">
                        <input type="text" name="code" id="inputCode" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 uppercase" placeholder="SUMMER25">
                        <button type="button" onclick="generateCode()" class="px-3 py-2 bg-gray-100 rounded border text-sm hover:bg-gray-200">Random</button>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Percentage (%)</label>
                    <input type="number" name="percentage" id="inputPercentage" min="1" max="100" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start</label>
                        <input type="datetime-local" name="start_date" id="inputStart" required class="w-full rounded-lg border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End</label>
                        <input type="datetime-local" name="end_date" id="inputEnd" required class="w-full rounded-lg border-gray-300 text-sm">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Save</button>
                </div>
            </form>
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

<script src="{{ asset('js/discount_modal.js') }}"></script>
@endsection