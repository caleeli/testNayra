<?php

use App\Facades\WorkflowManager;
use App\Process;
use App\Instance;
use App\Token;

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

Artisan::command('bpmn:callProcess {definitionsId} {processId}', function ($definitionsId, $processId) {
    $definitions = Process::where('uid', $definitionsId)->first();
    $process = $definitions->getDefinitions()->getProcess($processId);

    WorkflowManager::callProcess($definitions, $process);

})->describe('Run BPMN process');

Artisan::command('bpmn:completeTask {definitionsId} {processId} {instanceId} {tokenId}', function ($definitionsId, $processId, $instanceId, $tokenId) {
    $definitions = Process::where('uid', $definitionsId)->first();
    $process = $definitions->getDefinitions()->getProcess($processId);
    $instance = Instance::where('uid', $instanceId)->first();
    $token = Token::where('uid', $tokenId)->first();

    WorkflowManager::completeTask($definitions, $process, $instance, $token);

})->describe('Complete a instance-token');
