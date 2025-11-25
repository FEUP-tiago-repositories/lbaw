@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6 gap-6 lg:flex lg:flex-row">

    <div class="flex flex-col gap-6">
        @forelse ($spaces as $space)
            <a href="{{ route('spaces.show', $space->id) }}">
                <div class="bg-white border border-gray-200 shadow-lg rounded-xl p-6 w-full max-w-2xl flex gap-2">
                    
                    <div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                        <p>Space image</p>
                    </div>

                    <div class="flex flex-col justify-between flex-grow">
                        <h2 class="text-2xl font-semibold text-gray-900">{{$space->title}}</h2>
                        <p class="text-lg text-gray-700">{{$space->address}}</p>
                        <p class="text-xl text-gray-600">{{$space->SportType->name}}</p>
                        <p class="text-base text-gray-600">Next time available:</p>
                    </div>

                    <div class="flex flex-col items-end">
                        <p class="text-xl text-gray-600 text-right">Avaliation</p>
                        <p class="text-xl text-gray-600 text-right">Number of avaliations</p>
                    </div>
                </div>
            </a>
        @empty
            <p class="text-4xl font-bold text-left">No results found</p>
        @endforelse
    </div>

    <div class="hidden lg:block lg:w-1/2">
        <img class="w-full h-auto object-cover rounded-xl" src="/images/mapa.jpg">
    </div>

</div>
@endsection
