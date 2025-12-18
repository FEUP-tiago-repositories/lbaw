@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-[1600px] mx-auto">
            <!-- Breadcrumb -->
            <div class="flex items-center gap-2 mb-6 text-lg">
                <a href="{{ route('home') }}" class="text-emerald-600 hover:text-emerald-400">
                    <img alt="Home Page" src="/images/home-icon.svg" height="18" width="18">
                </a>
                <svg class="w-5 h-5 pt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('users.show', Auth::id()) }}" class="text-emerald-600 hover:text-emerald-400">
                    Profile of {{ Auth::user()->user_name }}
                </a>
                <svg class="w-5 h-5 pt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="{{ route('spaces.bookings.select')}}" class="text-emerald-600 hover:text-emerald-400">
                    My Sport Spaces
                </a>
                <svg class="w-5 h-5 pt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <p>Reservations for {{ $space->title }}</p>
            </div>
            
            <!-- Page Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Reservations for {{ $space->title }}</h1>
                    <p class="text-gray-600 mt-2">{{ $space->address }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('spaces.show', $space->id) }}"
                       class="bg-emerald-800 text-white px-8 py-2 rounded-lg hover:text-black hover:bg-emerald-200 font-medium transition">
                        View Space
                    </a>
                </div>
            </div>

            @if(!$hasAnyBookings)
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-600 text-lg">No reservations found for this space</p>
                </div>
            @else
                <!-- View Toggle Buttons -->
                <div class="flex gap-2 mb-6">
                    <button id="btn-list-view" onclick="switchView('list')"
                            class="inline-flex items-center view-btn px-4 py-2 rounded-lg font-medium transition bg-gray-200 text-gray-700 hover:bg-gray-400">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        List View
                    </button>
                    <button id="btn-calendar-view" onclick="switchView('calendar')"
                            class="inline-flex items-center view-btn px-4 py-2 rounded-lg font-medium transition bg-emerald-200 text-black hover:bg-emerald-400">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Calendar View
                    </button>
                </div>

                <!-- List View -->
                <div id="list-view" class="hidden">
                    <div class="mb-6">
                        <div class="border-b border-gray-200">
                            <nav class="flex gap-8">
                                <button onclick="showTab('future')" id="tab-future"
                                        class="tab-button border-b-2 border-emerald-800 text-emerald-800 py-2 px-1 text-center font-medium text-sm">
                                    Future ({{ $futureReservations->count() }})
                                </button>
                                <button onclick="showTab('past')" id="tab-past"
                                        class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-center font-medium text-sm">
                                    Past ({{ $pastReservations->count() }})
                                </button>
                                <button onclick="showTab('cancelled')" id="tab-cancelled"
                                        class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-center font-medium text-sm">
                                    Cancelled ({{ $cancelledReservations->count() }})
                                </button>
                            </nav>
                        </div>
                    </div>

                    <div id="content-future" class="tab-content">
                        @if($futureReservations->isEmpty())
                            <p class="text-gray-500 text-center py-8">No future reservations</p>
                        @else
                            <div class="grid grid-cols-[repeat(auto-fit,minmax(226px,1fr))] gap-4">
                                @foreach($futureReservations as $booking)
                                    @include('bookings.partials.booking-space-card', ['booking' => $booking])
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div id="content-past" class="tab-content hidden">
                        @if($pastReservations->isEmpty())
                            <p class="text-gray-500 text-center py-8">No past reservations</p>
                        @else
                            <div class="grid grid-cols-[repeat(auto-fit,minmax(226px,1fr))] gap-4">
                                @foreach($pastReservations as $booking)
                                    @include('bookings.partials.booking-space-card', ['booking' => $booking])
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div id="content-cancelled" class="tab-content hidden">
                        @if($cancelledReservations->isEmpty())
                            <p class="text-gray-500 text-center py-8">No cancelled reservations</p>
                        @else
                            <div class="grid grid-cols-[repeat(auto-fit,minmax(226px,1fr))] gap-4">
                                @foreach($cancelledReservations as $booking)
                                    @include('bookings.partials.booking-space-card', ['booking' => $booking])
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Calendar View -->
                <div id="calendar-view">
                    <!-- Week Filter Navigation -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <div class="flex items-center justify-between">
                            <button onclick="changeWeek(-1)" class="px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                last week
                            </button>
                            
                            <div class="flex items-center gap-4">
                                <!-- Day Selector -->
                                <select id="day-select" onchange="goToDate(this.value)" 
                                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                    @for($i = 1; $i <= 31; $i++)
                                        <option value="{{ $i }}" {{ $i == \Carbon\Carbon::parse($selectedDate)->day ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>

                                <!-- Month Selector -->
                                <select id="month-select" onchange="updateDateFromSelectors()" 
                                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                    @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $index => $month)
                                        <option value="{{ $index + 1 }}" {{ ($index + 1) == \Carbon\Carbon::parse($selectedDate)->month ? 'selected' : '' }}>
                                            {{ $month }}
                                        </option>
                                    @endforeach
                                </select>

                                <!-- Year Selector -->
                                <select id="year-select" onchange="updateDateFromSelectors()" 
                                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                    @for($year = 2024; $year <= 2026; $year++)
                                        <option value="{{ $year }}" {{ $year == \Carbon\Carbon::parse($selectedDate)->year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            
                            <button onclick="changeWeek(1)" class="px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition flex items-center gap-2">
                                next week
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Week Days -->
                        <div class="grid grid-cols-7 gap-2 mt-6">
                            @php
                                $weekDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                                $currentDayOfWeek = \Carbon\Carbon::parse($selectedDate)->dayOfWeekIso - 1;
                            @endphp
                            @foreach($weekDays as $index => $day)
                                <button onclick="goToDayOfWeek({{ $index }})" 
                                        class="text-center py-2 rounded-lg font-semibold transition
                                               {{ $index == $currentDayOfWeek ? 'bg-emerald-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                                    {{ $day }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Split Layout: Timeline (1/3) + Details Panel (2/3) -->
                    <div class="grid grid-cols-3 gap-6">
                        <!-- Timeline Column (1/3) -->
                        <div class="col-span-1 bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="p-4 border-b border-gray-200 bg-gray-50">
                                <h3 class="font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($selectedDate)->format('l, F j') }}
                                </h3>
                            </div>
                            <div class="overflow-y-auto" style="max-height: 800px;">
                                @if($timeline->isEmpty())
                                    <div class="p-8 text-center">
                                        <p class="text-gray-500">No time slots for this day</p>
                                    </div>
                                @else
                                    @foreach($timeline as $slot)
                                        @php
                                            $percentage = $slot['occupancy_percentage'];
                                            if ($percentage >= 90) {
                                                $bgColor = 'bg-red-50 border-red-300 hover:bg-red-100';
                                                $textColor = 'text-red-800';
                                                $dotColor = 'bg-red-500';
                                            } elseif ($percentage >= 70) {
                                                $bgColor = 'bg-yellow-50 border-yellow-300 hover:bg-yellow-100';
                                                $textColor = 'text-yellow-800';
                                                $dotColor = 'bg-yellow-500';
                                            } else {
                                                $bgColor = 'bg-green-50 border-green-300 hover:bg-green-100';
                                                $textColor = 'text-green-800';
                                                $dotColor = 'bg-green-500';
                                            }
                                        @endphp
                                        <button onclick="showSlotDetails({{ $slot['schedule']->id }})" 
                                                data-slot-id="{{ $slot['schedule']->id }}"
                                                class="slot-button w-full text-left p-3 border-b border-l-4 {{ $bgColor }} transition cursor-pointer">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-2 h-2 rounded-full {{ $dotColor }}"></div>
                                                    <span class="font-semibold {{ $textColor }}">{{ $slot['time'] }}</span>
                                                </div>
                                                <div class="text-xs {{ $textColor }} font-medium">
                                                    {{ $slot['used_capacity'] }}/{{ $slot['total_capacity'] }}
                                                </div>
                                            </div>
                                            @if($slot['has_bookings'])
                                                <div class="mt-1 text-xs text-gray-600">
                                                    {{ $slot['bookings']->count() }} {{ $slot['bookings']->count() === 1 ? 'booking' : 'bookings' }}
                                                </div>
                                            @endif
                                        </button>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- Details Panel (2/3) -->
                        <div class="col-span-2 bg-white rounded-lg shadow-md overflow-hidden">
                            <div id="details-panel" class="h-full">
                                <div class="flex items-center justify-center h-full text-gray-400 p-12">
                                    <div class="text-center">
                                        <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                                        </svg>
                                        <p class="text-lg font-medium text-gray-500">Select a time slot to view details</p>
                                        <p class="text-sm text-gray-400 mt-2">Click on any time slot in the timeline</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden booking cards data -->
                    <div class="hidden">
                        @foreach($timeline as $slot)
                            @if($slot['has_bookings'])
                                <div id="slot-data-{{ $slot['schedule']->id }}" class="slot-data">
                                    <div class="p-6">
                                        <div class="mb-4">
                                            <h3 class="text-2xl font-bold text-gray-900">{{ $slot['time'] }}</h3>
                                            <p class="text-gray-600">{{ $slot['schedule']->duration }} minutes</p>
                                        </div>
                                        
                                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                            <div class="flex items-center justify-between">
                                                <span class="text-gray-700 font-medium">Capacity</span>
                                                <span class="text-lg font-bold">{{ $slot['used_capacity'] }}/{{ $slot['total_capacity'] }} persons</span>
                                            </div>
                                            <div class="mt-2 w-full bg-gray-200 rounded-full h-3">
                                                <div class="h-3 rounded-full transition-all duration-300
                                                    @if($slot['occupancy_percentage'] >= 90) bg-red-500
                                                    @elseif($slot['occupancy_percentage'] >= 70) bg-yellow-500
                                                    @else bg-green-500 @endif" 
                                                     style="width: {{ min($slot['occupancy_percentage'], 100) }}%"></div>
                                            </div>
                                            <div class="mt-2 text-sm text-gray-600">
                                                {{ $slot['available_capacity'] }} spots available
                                            </div>
                                        </div>

                                        <h4 class="font-semibold text-gray-900 mb-4">Bookings ({{ $slot['bookings']->count() }})</h4>
                                        <div class="space-y-4">
                                            @foreach($slot['bookings'] as $booking)
                                                @include('bookings.partials.booking-space-card', ['booking' => $booking])
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div id="slot-data-{{ $slot['schedule']->id }}" class="slot-data">
                                    <div class="flex items-center justify-center h-full text-gray-400 p-12">
                                        <div class="text-center">
                                            <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <p class="text-lg font-medium text-gray-500">No bookings for {{ $slot['time'] }}</p>
                                            <p class="text-sm text-gray-400 mt-2">{{ $slot['total_capacity'] }} spots available</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
    @include('bookings.modals.cancel-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/booking.js') }}"></script>
    <script src="{{ asset('js/enhanced-calendar.js') }}"></script>
@endpush