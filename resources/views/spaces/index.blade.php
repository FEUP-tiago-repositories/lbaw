{{-- Will render the page with all sport spaces! --}}
@extends('layouts.app')
@section('title', 'Sport Spaces - Sports Hub')
@section('content')
    {{-- -Main section --}}
    <div class="container mx-auto px-8 py-8">
        <h1 class="text-3xl font-bold mb-6">Take a look at our Spaces!</h1>
        {{-- -Map section --}}
        <div class="h-64 max-w-7xl mx-auto mb-8 border-2">
            <img src="{{ asset('images/map(mockup2).png') }}" alt="Map" class="w-full h-full object-cover">
        </div>

        {{-- -This section will be used for the Spaces Grid --}}
        {{-- -We will use the predefined space card partial for each space --}}
        <div class="flex overflow-x-auto flex-wrap gap-2 justify-center">
            @forelse ($spaces as $space)
                @include('spaces.partials.space-card', ['space' => $space])
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 text-xl">No spaces found.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection