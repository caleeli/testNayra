<?php
namespace App\Providers;

use App\BpmnEngine;
use App\Listeners\BpmnSubscriber;
use App\Managers\WorkflowManager;
use App\Repositories\DefinitionsRepository;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use ProcessMaker\Nayra\Contracts\Storage\BpmnDocumentInterface;
use ProcessMaker\Nayra\Storage\BpmnDocument;
use Illuminate\Support\Facades\Log;

class WorkflowServiceProvider extends ServiceProvider
{

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        BpmnSubscriber::class,
    ];

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
            return new WorkflowManager();
        });
        /** 
         * BpmnDocument Process Context
         */
        $this->app->bind(BpmnDocumentInterface::class, function ($app, $params) {
            Log::debug('BPMN Document instantiated');
            $repository = new DefinitionsRepository();
            $eventBus = app('events');

            //Initialize the BpmnEngine
            $engine = new BpmnEngine($repository, $eventBus);

            //Initialize BpmnDocument repository (REQUIRES $engine $factory)
            $bpmnRepository = new BpmnDocument();
            $bpmnRepository->setEngine($engine);
            $bpmnRepository->setFactory($repository);
            $engine->setStorage($bpmnRepository);

            return $bpmnRepository;
        });
    }
}
