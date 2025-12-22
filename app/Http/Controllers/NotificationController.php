<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with([
                'bookingConfirmation', 
                'bookingCancellation', 
                'bookingReminder'
            ])
            ->where('user_id', Auth::id())
            ->orderBy('time_stamp', 'desc')
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        
        $notification->update(['is_read' => true]);

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'All notifications have been marked as read.');
    }

    public function destroy($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notification deleted.');
    }

    public function checkLatest()
    {
        if (!auth()->check()) {
            return response()->json(['id' => null]);
        }

        $latest = auth()->user()->notifications()
            ->where('is_read', false) 
            ->orderBy('time_stamp', 'desc')
            ->first();

        if ($latest) {

            $style = $latest->style; 

            $colors = [
                'emerald' => '#10b981',
                'yellow'  => '#eab308',
                'blue'    => '#3b82f6',
                'red'     => '#ef4444',
            ];
            $hexColor = $colors[$style['color']] ?? '#6b7280';

            $iconHtml = '<svg xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="' . $style['icon'] . '" />
                        </svg>';

            return response()->json([
                'id' => $latest->id,
                'title' => $style['title'],
                'content' => $latest->content,
                'icon_html' => $iconHtml,
                'color' => $hexColor
            ]);
        }

        return response()->json(['id' => null]);
    }
}