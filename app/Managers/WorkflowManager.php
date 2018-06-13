<?php

namespace App\Managers;

use App\BpmnEngine;

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

    public function callProcess()
    {

    }

    public function runScripTask()
    {

    }
}