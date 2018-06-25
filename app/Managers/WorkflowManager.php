<?php

namespace App\Managers;

use App\Jobs\CallProcess;
use App\Jobs\CompleteActivity;
use App\Process as Definitions;
use ProcessMaker\Nayra\Contracts\Bpmn\ProcessInterface;
use ProcessMaker\Nayra\Contracts\Engine\ExecutionInstanceInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\TokenInterface;

class WorkflowManager
{

    public function completeTask(Definitions $definitions, ExecutionInstanceInterface $instance, TokenInterface $token)
    {
        CompleteActivity::dispatch($definitions, $instance, $token);
    }

    public function triggerEvent()
    {

    }

    public function callProcess(Definitions $definitions, ProcessInterface $process)
    {
        //Validate user permissions
        //Validate BPMN rules
        //Log BPMN actions
        //Schedule BPMN Action
        CallProcess::dispatch($definitions, $process);
    }

    public function runScripTask($filename, $processId, $instanceId, $tokenId)
    {
    }
}
