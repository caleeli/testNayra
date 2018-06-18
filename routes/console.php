<?php

use App\Facades\WorkflowManager;

/*
  |--------------------------------------------------------------------------
  | Console Routes
  |--------------------------------------------------------------------------
  |
  | This file is where you may define all of your Closure based console
  | commands. Each Closure is bound to a command instance allowing a
  | simple approach to interacting with each command's IO methods.
  |
 */

Artisan::command('bpmn {filename} {process}', function ($filename, $process) {

    WorkflowManager::callProcess($filename, $process);

})->describe('Run BPMN');
