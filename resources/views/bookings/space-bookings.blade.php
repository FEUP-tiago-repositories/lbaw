@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
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
                <p>
                    Reservations for {{ $space->title }}
                </p>
            </div>
            <!-- Header com informação do espaço -->
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Reservations for {{ $space->title }}</h1>
                    <p class="text-gray-600 mt-2">{{ $space->address }}</p>
                </div>
                <!-- View Toggle -->
                <div class="flex space-x-2">
                    <a href="{{ route('spaces.show', $space->id) }}"
                       class="bg-emerald-800 text-white px-8 py-2 rounded-lg hover:text-black hover:bg-emerald-200 font-medium transition">
                        View Space
                    </a>
                </div>
            </div>

            @if($futureReservations->isEmpty() && $pastReservations->isEmpty() && $cancelledReservations->isEmpty())
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-600 text-lg">No reservations found for this space</p>
                </div>
            @else
                <!-- View Toggle Buttons -->
                <div class="flex gap-2 my-4">
                    <button id="btn-list-view" onclick="switchView('list')"
                            class="inline-flex items-center view-btn px-4 py-2 rounded-lg font-medium transition bg-emerald-200 text-black hover:bg-emerald-400">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        List
                    </button>
                    <button id="btn-calendar-view" onclick="switchView('calendar')"
                            class="inline-flex items-center view-btn px-4 py-2 rounded-lg font-medium transition bg-gray-200 text-gray-700 hover:bg-gray-400">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Calendar
                    </button>
                </div>
                <!-- List View -->
                <div id="list-view">
                    <!-- Tabs -->
                    <div class="mb-6">
                        <div class="border-b border-gray-200">
                            <nav class="flex gap-8">
                                <button onclick="showTab('future')"
                                        id="tab-future"
                                        class="tab-button border-b-2 border-emerald-800 text-emerald-800 py-2 px-1 text-center font-medium text-sm">
                                    Future ({{ $futureReservations->count() }})
                                </button>
                                <button onclick="showTab('past')"
                                        id="tab-past"
                                        class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-center font-medium text-sm">
                                    Past ({{ $pastReservations->count() }})
                                </button>
                                <button onclick="showTab('cancelled')"
                                        id="tab-cancelled"
                                        class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-center font-medium text-sm">
                                    Cancelled ({{ $cancelledReservations->count() }})
                                </button>
                            </nav>
                        </div>
                    </div>

                    <!-- Future Reservations -->
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

                    <!-- Past Reservations -->
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

                    <!-- Cancelled Reservations -->
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
                <div id="calendar-view" class="hidden">
                    <!-- Calendar Header -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <button id="prev-month" class="p-2 hover:bg-gray-100 rounded-lg transition">
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <h2 id="calendar-month-year" class="text-2xl font-bold text-gray-900"></h2>
                            <button id="next-month" class="p-2 hover:bg-gray-100 rounded-lg transition">
                                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Calendar Grid -->
                        <div class="grid grid-cols-7 gap-2">
                            <!-- Day Headers -->
                            <div class="text-center font-semibold text-gray-700 py-2">Sun</div>
                            <div class="text-center font-semibold text-gray-700 py-2">Mon</div>
                            <div class="text-center font-semibold text-gray-700 py-2">Tue</div>
                            <div class="text-center font-semibold text-gray-700 py-2">Wed</div>
                            <div class="text-center font-semibold text-gray-700 py-2">Thu</div>
                            <div class="text-center font-semibold text-gray-700 py-2">Fri</div>
                            <div class="text-center font-semibold text-gray-700 py-2">Sat</div>

                            <!-- Calendar Days (42 cells for 6 weeks) -->
                            @for ($i = 0; $i < 42; $i++)
                                <div class="calendar-day-cell aspect-square border border-gray-200 rounded-lg p-2 hover:bg-gray-50 transition cursor-pointer relative hidden"
                                     data-day-index="{{ $i }}">
                                    <div class="calendar-day-number text-sm font-medium text-gray-900"></div>
                                    <div class="calendar-day-indicator mt-1 flex flex-wrap gap-1"></div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Bookings for Selected Day -->
                    <div id="selected-day-bookings" class="hidden">
                        <h3 id="selected-day-title" class="text-xl font-bold text-gray-900 mb-4"></h3>
                        <div id="selected-day-bookings-list" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            <!-- Bookings will be populated here by JavaScript -->
                        </div>
                    </div>

                    <!-- Hidden booking list templates for calendar -->
                    <div class="hidden">
                        @foreach($futureReservations->merge($pastReservations)->merge($cancelledReservations) as $booking)
                            <div class="booking-card-template"
                                 data-booking-id="{{ $booking->id }}"
                                 data-booking-date="{{ \Carbon\Carbon::parse($booking->schedule->start_time)->format('Y-m-d') }}"
                                 data-booking-status="{{ $booking->is_cancelled ? 'cancelled' : ($booking->isFuture() ? 'future' : 'past') }}">
                                @include('bookings.partials.booking-space-card', ['booking' => $booking])
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Hidden data for JavaScript -->
                <script type="application/json" id="bookings-data">
                    [
                    @foreach($futureReservations->merge($pastReservations)->merge($cancelledReservations) as $booking)
                        {"id": {{ $booking->id }},
                        "date": "{{ \Carbon\Carbon::parse($booking->schedule->start_time)->format('Y-m-d') }}",
                        "time": "{{ \Carbon\Carbon::parse($booking->schedule->start_time)->format('H:i') }}",
                        "customer": "{{ $booking->customer->user->name }}",
                        "status": "{{ $booking->is_cancelled ? 'cancelled' : ($booking->isFuture() ? 'future' : 'past') }}",
                        "duration": {{ $booking->total_duration }},
                        "persons": {{ $booking->number_of_persons }}
                        }{{ !$loop->last ? ',' : '' }}
                    @endforeach
                    ]
                </script>
            @endif
        </div>
    </div>
    @include('bookings.modals.cancel-modal')
    <script>
        function showTab(tabName) {
            // Hide all content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active styling from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-emerald-800', 'text-emerald-800');
                button.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected content
            document.getElementById('content-' + tabName).classList.remove('hidden');

            // Add active styling to selected tab
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-emerald-800', 'text-emerald-800');
        }

        // View switching function
        function switchView(view) {
            const listView = document.getElementById('list-view');
            const calendarView = document.getElementById('calendar-view');
            const btnList = document.getElementById('btn-list-view');
            const btnCalendar = document.getElementById('btn-calendar-view');

            if (view === 'list') {
                listView.classList.remove('hidden');
                calendarView.classList.add('hidden');
                btnList.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-400');
                btnList.classList.add('bg-emerald-200', 'text-black', 'hover:bg-emerald-400');
                btnCalendar.classList.remove('bg-emerald-200', 'text-black', 'hover:bg-emerald-400');
                btnCalendar.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-400');
            } else {
                listView.classList.add('hidden');
                calendarView.classList.remove('hidden');
                btnList.classList.remove('bg-emerald-200', 'text-black', 'hover:bg-emerald-400');
                btnList.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-400');
                btnCalendar.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-400');
                btnCalendar.classList.add('bg-emerald-200', 'text-black', 'hover:bg-emerald-400');

                // Initialize calendar when switching to it
                if (typeof initializeCalendar === 'function') {
                    initializeCalendar();
                }
            }
        }
    </script>
@endsection

@push('scripts')
    <script src="{{ asset('js/booking.js') }}"></script>
    <script src="{{ asset('js/calendar.js') }}"></script>
@endpush