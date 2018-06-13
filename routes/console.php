<?php

use ProcessMaker\Nayra\Storage\BpmnDocument;
use App\BpmnEngine;
use ProcessMaker\Nayra\Factory;

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

Artisan::command('bpmn', function (BpmnEngine $engine) {

    $bpmnRepository = bootBpmnRepository($engine);

    //Get the process object (REQUIRES Load the BPMN file)
    $process = $bpmnRepository->getProcess('PROCESS_1');

    //Start a process (REQUIRES a process reference)
    $process->call();
    $bpmnRepository->getEngine()->runToNextState();

})->describe('Run BPMN');

function bootBpmnRepository(BpmnEngine $engine) {
    $factory = $engine->getFactory();

    //Initialize BpmnDocument repository (REQUIRES $engine $factory)
    $bpmnRepository = new BpmnDocument();
    $bpmnRepository->setEngine($engine);
    $bpmnRepository->setFactory($factory);

    //Load the BPMN file
    $bpmnRepository->load('bpmn/Lanes.bpmn');

    return $bpmnRepository;
}
