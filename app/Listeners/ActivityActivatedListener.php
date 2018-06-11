<?php

namespace App\Listeners;

use App\Events\ActivityActivatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivityActivatedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ActivityActivatedEvent  $event
     * @return void
     */
    public function handle(ActivityActivatedEvent $event)
    {
        //$bpmnRepository = bootBpmnRepository();
        $token = $event->token;
        $token->uid = $token->getId();
        $token->status = $token->getStatus();
        $token->element_ref = $event->activity->getId();
        $token->instance_id = $token->getInstance()->getId();

        dump($token->getProperties());
    }
}
