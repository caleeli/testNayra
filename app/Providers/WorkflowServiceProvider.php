<?php
namespace App\Providers;

use App\BpmnEngine;
use App\BpmnEventBus;
use App\BpmnFactory;
use App\Managers\WorkflowManager;
use ProcessMaker\Nayra\Storage\BpmnDocument;
use ProcessMaker\Nayra\Contracts\Storage\BpmnDocumentInterface;

class WorkflowServiceProvider extends EventServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * BPMN Workflow Manager
         */
        $this->app->singleton('workflow.manager', function ($app) {
            return new WorkflowManager($app->make(BpmnEngine::class));
        });
        /**
         * BPMN Factory
         */
        $this->app->singleton(BpmnFactory::class, function () {
            return new BpmnFactory(BpmnFactory::mapping);
        });
        /**
         * BPMN Event Bus
         */
        $this->app->singleton(BpmnEventBus::class, function () {
            return new BpmnEventBus(app('events'));
        });
        /**
         * BPMN Engine
         */
        $this->app->singleton(BpmnEngine::class, function ($app) {
            $factory = $app->make(BpmnFactory::class);
            $eventBus = $app->make(BpmnEventBus::class);

            //Initialize the BpmnEngine
            $engine = new BpmnEngine($factory, $eventBus);

            return $engine;
        });
        /**
         * BPMN Storage
         */
        $this->app->bind(BpmnDocumentInterface::class, function ($app) {
            $engine = $app->make(BpmnEngine::class);
            $factory = $app->make(BpmnFactory::class);

            //Initialize BpmnDocument repository (REQUIRES $engine $factory)
            $bpmnRepository = new BpmnDocument();
            $bpmnRepository->setEngine($engine);
            $bpmnRepository->setFactory($factory);

            return $bpmnRepository;
        });
    }
}
