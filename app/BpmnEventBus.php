<?php

namespace App;

use ProcessMaker\Nayra\Contracts\EventBusInterface;
use ProcessMaker\Nayra\Bpmn\Models\ActivityActivatedEvent;

/**
 * Description of Dispatcher
 *
 */
class BpmnEventBus implements EventBusInterface
{
    /**
     * @var \Illuminate\Events\Dispatcher $dispacher
     */
    private $dispatcher;

    public function __construct(/*$dispatcher*/)
    {
        //$this->dispatcher = $dispatcher;
        $this->dispatcher = app('events');
    }

    public function dispatch($event, $payload = array(), $halt = false)
    {
        return $this->dispatcher->dispatch($event, $payload, $halt);
    }

    public function flush($event)
    {
        return $this->dispatcher->flush($event);
    }

    public function forget($event)
    {
        return $this->dispatcher->forget($event);
    }

    public function forgetPushed()
    {
        return $this->dispatcher->forgetPushed();
    }

    public function hasListeners($eventName): bool
    {
        return $this->dispatcher->hasListeners($eventName);
    }

    public function listen($events, $listener)
    {
        return $this->dispatcher->listen($events, $listener);
    }

    public function push($event, $payload = array())
    {
        return $this->dispatcher->push($event, $payload);
    }

    public function subscribe($subscriber)
    {
        return $this->dispatcher->subscribe($subscriber);
    }

    public function until($event, $payload = array())
    {
        return $this->dispatcher->until($event, $payload);
    }

    function __sleep()
    {
        return [];
    }

    function __wakeup()
    {
        $this->dispatcher = app('events');
    }
}
