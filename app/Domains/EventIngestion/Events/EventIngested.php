<?php

namespace App\Domains\EventIngestion\Events;

use App\Domains\Projects\Models\Event;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EventIngested implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Event $event)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('project.' . $this->event->project_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'event.ingested';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->event->id,
            'event_id' => $this->event->event_id,
            'event_name' => $this->event->event_name,
            'event_time' => $this->event->event_time->format('Y-m-d H:i:s'),
            'user_data' => $this->event->user_data,
            'custom_data' => $this->event->custom_data,
        ];
    }
}
