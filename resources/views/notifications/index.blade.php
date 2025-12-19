@extends('layouts.app')
@section('title', 'Notifications - Sport Spaces')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    
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
        <p class="text-gray-600">
            Notifications
        </p>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <h1 class="text-4xl font-bold text-gray-900">My Notifications</h1>
        
        @if($notifications->where('is_read', false)->count() > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-white border border-emerald-600 text-emerald-600 rounded-lg hover:bg-emerald-50 transition-colors shadow-sm text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Mark all as read
                </button>
            </form>
        @endif
    </div>

    @if($notifications->isEmpty())
        {{-- Empty State --}}
        <div class="text-center py-16 bg-gray-50 rounded-xl border border-dashed border-gray-300">
            <div class="mx-auto h-16 w-16 text-gray-400 mb-4">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>
            <p class="text-xl text-gray-600 font-medium">No notifications found</p>
            <p class="text-sm text-gray-500 mt-2">You're all caught up!</p>
        </div>
    @else
        <div class="flex flex-col gap-4">
            @foreach($notifications as $notification)

                <div class="relative flex items-start p-4 rounded-xl border transition-all duration-200 
                    {{ $notification->is_read ? 'bg-gray-50 border-gray-200' : 'bg-white shadow-md border-l-4 border-l-'.$notification->style['color'].'-500 border-gray-100 transform hover:-translate-y-0.5' }}">
                    
                    <div class="flex-shrink-0 mr-4">
                        <div class="p-2 rounded-full bg-{{ $notification->style['color'] }}-100 text-{{ $notification->style['color'] }}-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $notification->style['icon'] }}"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="text-base font-semibold {{ $notification->is_read ? 'text-gray-600' : 'text-gray-900' }}">
                                {{ $notification->style['title'] }}
                            </h3>
                            <span class="text-xs text-gray-500 whitespace-nowrap ml-2">
                                {{ $notification->time_stamp->diffForHumans() }}
                            </span>
                        </div>
                        
                        <p class="text-sm {{ $notification->is_read ? 'text-gray-500' : 'text-gray-700' }} mb-2">
                            {{ $notification->content }}
                        </p>

                    </div>

                    <div class="flex flex-col gap-2 ml-4 border-l pl-4 border-gray-100">
                        @if(!$notification->is_read)
                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-emerald-600 hover:text-emerald-800 p-1 rounded-md hover:bg-emerald-50 transition-colors" title="Mark as read">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4.5 12.75 6 6 9-13.5"></path></svg>
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete the notification?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-500 p-1 rounded-md hover:bg-red-50 transition-colors" title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="red" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"></path></svg>
                            </button>
                        </form>
                    </div>

                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection