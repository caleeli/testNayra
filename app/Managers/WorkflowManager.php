<?php

namespace App\Managers;

use App\BpmnEngine;
use App\Jobs\CallProcess;

class WorkflowManager
{
    private $engine;

    public function __construct(BpmnEngine $engine)
    {
        $this->engine = $engine;
    }

    public function completeTask($taskId, $tokenId)
    {

    }

    public function triggerEvent()
    {

    }

    public function callProcess($filename, $processId)
    {
        CallProcess::dispatch($filename, $processId);
    }

    public function runScripTask()
    {

    }
}