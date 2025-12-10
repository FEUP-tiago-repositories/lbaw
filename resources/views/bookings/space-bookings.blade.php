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
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Reservations for {{ $space->title }}</h1>
                    <p class="text-gray-600 mt-2">{{ $space->address }}</p>
                </div>
                <a href="{{ route('spaces.show', $space->id) }}"
                   class="bg-emerald-800 text-white px-8 py-2 rounded-lg hover:text-black hover:bg-emerald-200 font-medium transition">
                    View Space
                </a>
            </div>

            @if($futureReservations->isEmpty() && $pastReservations->isEmpty() && $cancelledReservations->isEmpty())
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-600 text-lg">No reservations found for this space</p>
                </div>
            @else
                <!-- Tabs -->
                <div class="mb-6">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8">
                            <button onclick="showTab('future')"
                                    id="tab-future"
                                    class="tab-button border-b-2 border-emerald-800 text-emerald-800 py-4 px-1 text-center font-medium text-sm">
                                Future ({{ $futureReservations->count() }})
                            </button>
                            <button onclick="showTab('past')"
                                    id="tab-past"
                                    class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-center font-medium text-sm">
                                Past ({{ $pastReservations->count() }})
                            </button>
                            <button onclick="showTab('cancelled')"
                                    id="tab-cancelled"
                                    class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-center font-medium text-sm">
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
    </script>
@endsection

@push('scripts')
    <script src="{{ asset('js/booking.js') }}"></script>
@endpush