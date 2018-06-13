<?php

namespace App\Providers;



use App\BpmnEngine;
use App\Managers\WorkflowManager;

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
        $this->app->singleton('workflow.manager', function ($app) {
            return new WorkflowManager($app->make(BpmnEngine::class));
        });
    }
}