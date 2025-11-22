@extends('layouts.app')

@section('content')
<div class = "pt-56 text-center">
    <img class="mx-auto block pt-4" src="/images/praia.jpg" alt="🏖️">
    <h2>Best Reviewed:</h2>
    <div class="featured-spaces pt-8">
        @foreach ($spaces as $space)
            <div class="space-card">
                <h3 class="space name">{{ $space->title }}</h3>
                <p class="space address">{{ $space->address }}</p>
                <p class="space num_favorites">Favorites: {{ $space->num_favorites }}</p>
            </div>
        @endforeach
    </div>
</div>
@endsection
