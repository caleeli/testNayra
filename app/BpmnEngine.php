<?php

namespace App;

use ProcessMaker\Nayra\Contracts\Engine\EngineInterface;
use ProcessMaker\Nayra\Contracts\EventBusInterface;
use ProcessMaker\Nayra\Contracts\FactoryInterface;
use ProcessMaker\Nayra\Engine\EngineTrait;

/**
 * Test implementation for EngineInterface.
 *
 * @package App
 */
class BpmnEngine implements EngineInterface
{
    use EngineTrait;

    /**
     * @var RepositoryFactoryInterface
     */
    private $factory;

    /**
     * @var EventBusInterface $dispatcher
     */
    protected $dispatcher;

    /**
     * Test engine constructor.
     *
     * @param FactoryInterface $factory
     * @param EventBusInterface $dispatcher
     */
    public function __construct(FactoryInterface $factory, EventBusInterface $dispatcher)
    {
        $this->factory = $factory;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return EventBusInterface
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @param EventBusInterface $dispatcher
     *
     * @return $this
     */
    public function setDispatcher(EventBusInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        return $this;
    }

    /**
     * @return FactoryInterface
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @param FactoryInterface $factory
     *
     * @return $this
     */
    public function setFactory(FactoryInterface $factory)
    {
        $this->factory = $factory;
        return $this;
    }
}
