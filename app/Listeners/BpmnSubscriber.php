<?php
namespace App\Listeners;

use ProcessMaker\Nayra\Contracts\Bpmn\ActivityInterface;
use ProcessMaker\Nayra\Bpmn\Events\ActivityActivatedEvent;
use ProcessMaker\Nayra\Bpmn\Events\ActivityCompletedEvent;
use ProcessMaker\Nayra\Bpmn\Events\ActivityClosedEvent;
use ProcessMaker\Nayra\Contracts\Bpmn\ProcessInterface;
use ProcessMaker\Nayra\Bpmn\Events\ProcessInstanceCreatedEvent;
use Illuminate\Support\Facades\Log;

/**
 * Description of BpmnSubscriber
 *
 */
class BpmnSubscriber
{

    /**
     * When a process instance is created.
     *
     * @param ProcessInstanceCreatedEvent $event
     */
    public function onProcessCreated(ProcessInstanceCreatedEvent $event)
    {
        $event->instance->uid = $event->instance->getId();
        $event->instance->callable_id = $event->instance->getProcess()->getId();
        $event->instance->save();
        Log::info('ProcessCreated: ' . json_encode($event->instance->getProperties()));
    }

    /**
     * When an activity is activated.
     *
     * @param ActivityActivatedEvent $event
     */
    public function onActivityActivated(ActivityActivatedEvent $event)
    {
        $token = $event->token;
        $token->uid = $token->getId();
        $token->status = $token->getStatus();
        $token->element_ref = $event->activity->getId();
        $token->instance_id = $token->getInstance()->id;
        $token->save();
        Log::info('ActivityActivated: ' . json_encode($token->getProperties()));
    }

    /**
     * When an activity is completed.
     *
     * @param $event
     */
    public function onActivityCompleted(ActivityCompletedEvent $event)
    {
        $token = $event->token;
        $token->uid = $token->getId();
        $token->status = $token->getStatus();
        $token->element_ref = $event->activity->getId();
        $token->instance_id = $token->getInstance()->id;
        $token->save();
        Log::info('ActivityCompleted: ' . json_encode($token->getProperties()));
    }

    /**
     * When an activity is closed.
     *
     * @param $event
     */
    public function onActivityClosed(ActivityClosedEvent $event)
    {
        $token = $event->token;
        $token->uid = $token->getId();
        $token->status = $token->getStatus();
        $token->element_ref = $event->activity->getId();
        $token->instance_id = $token->getInstance()->id;
        $token->save();
        Log::info('ActivityClosed: ' . json_encode($token->getProperties()));
    }

    /**
     * Subscription.
     *
     * @param type $events
     */
    public function subscribe($events)
    {
        $events->listen(ProcessInterface::EVENT_PROCESS_INSTANCE_CREATED, static::class . '@onProcessCreated');
        $events->listen(ProcessInterface::EVENT_PROCESS_INSTANCE_COMPLETED, static::class . '@onProcessCompleted');

        $events->listen(ActivityInterface::EVENT_ACTIVITY_ACTIVATED, static::class . '@onActivityActivated');
        $events->listen(ActivityInterface::EVENT_ACTIVITY_COMPLETED, static::class . '@onActivityCompleted');
        $events->listen(ActivityInterface::EVENT_ACTIVITY_CLOSED, static::class . '@onActivityClosed');
    }
}
