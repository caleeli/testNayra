<?php

namespace App\Managers;

use App\BpmnEngine;
use App\Jobs\CallProcess;
use App\Jobs\CompleteActivity;

class WorkflowManager
{

    public function completeTask($filename, $processId, $instanceId, $tokenId)
    {
        $instance = \App\Instance::find($instanceId);
        $token = \App\Token::find($tokenId);
        CompleteActivity::dispatch($filename, $processId, $instance, $token);
    }

    public function triggerEvent()
    {

    }

    public function callProcess($filename, $processId)
    {
        //Validate user permissions
        //Validate BPMN rules
        //Log BPMN actions
        //Schedule BPMN Action
        CallProcess::dispatch($filename, $processId);
    }

    public function runScripTask($filename, $processId, $instanceId, $tokenId)
    {
    }
}
