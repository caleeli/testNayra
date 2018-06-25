<?php
namespace App\Jobs;

use ProcessMaker\Nayra\Contracts\Storage\BpmnDocumentInterface;
use ProcessMaker\Nayra\Contracts\Engine\ExecutionInstanceInterface;

abstract class InstanceAction extends ProcessAction
{

    public $instanceId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filename, $processId, ExecutionInstanceInterface $instance)
    {
        parent::__construct($filename, $processId);
        $this->instanceId = $instance->uid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(BpmnDocumentInterface $workflow)
    {
        try {
            //Load the process definition
            $workflow->load($this->filename);

            //Get the reference to the process
            $process = $workflow->getProcess($this->processId);

            //Load process instance
            $instance = $workflow->getEngine()->loadExecutionInstance($this->instanceId);

            //Do the action
            App::call([$this, 'action'], compact('workflow', 'process', 'instance'));

            //Run engine to the next state
            $workflow->getEngine()->runToNextState();
        } catch (\Throwable $t) {
            dd($t);
        }
    }
}
