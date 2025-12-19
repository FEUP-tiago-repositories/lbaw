<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.User.' . $this->notification->user_id);
    }
    
    public function broadcastWith()
    {
        return [
            'id' => $this->notification->id,
            'content' => $this->notification->content,
            'time' => $this->notification->time_stamp,
            'unread_count' => \App\Models\Notification::where('user_id', $this->notification->user_id)
                                                      ->where('is_read', false)->count()
        ];
    }
}