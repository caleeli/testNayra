<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use ProcessMaker\Nayra\Bpmn\Models\ActivityActivatedEvent as Event;

class ActivityActivatedEvent
{

    use Dispatchable,
        InteractsWithSockets,
        SerializesModels;
    public $activityId;
    public $activity;
    public $token;
    public $tokenId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Event $event)
    {
        $this->activity = $event->activity;
        $this->activityId = $event->activity->getId();
        $this->token = $event->token;
        $this->tokenId = $event->token->getId();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
