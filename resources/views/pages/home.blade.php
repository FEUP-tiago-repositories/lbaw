@extends('layouts.app')

@section('content')
<div class = "container mx-auto px-8 py-8">

    <div class="h-64 max-w-7xl mx-auto mb-8 border-2">
            <img src="/images/mapa.jpg" alt="Map" class="w-full h-full object-cover">
    </div>
    
    <h2 class = "mb-4 text-3xl font-semibold">Best Reviewed:</h2>
    
    <div class="flex overflow-x-auto gap-2 justify-center">
        @foreach ($spaces as $space)
            @include('spaces.partials.space-card', ['space' => $space])
        @endforeach
    </div>
</div>
@endsection
