<?php

namespace App\Providers;

use App\BpmnEngine;
use App\BpmnEventBus;
use App\BpmnFactory;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use ProcessMaker\Nayra\Contracts\Bpmn\ActivityInterface;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BpmnFactory::class,
                              function () {
            return new BpmnFactory(BpmnFactory::mapping);
        });
        $this->app->singleton(BpmnEventBus::class,
                              function () {
            return new BpmnEventBus(app('events'));
        });
        $this->app->singleton(BpmnEngine::class,
                              function ($app) {
            $factory = $app->make(BpmnFactory::class);
            $eventBus = $app->make(BpmnEventBus::class);

            //Initialize the BpmnEngine (REQUIRES $factory $busEvents)
            $engine = new BpmnEngine($factory, $eventBus);

            return $engine;
        });
    }
}
