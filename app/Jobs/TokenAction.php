<?php
namespace App\Jobs;

use ProcessMaker\Nayra\Contracts\Storage\BpmnDocumentInterface;
use ProcessMaker\Nayra\Contracts\Engine\ExecutionInstanceInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\TokenInterface;

abstract class TokenAction extends InstanceAction
{

    public $tokenId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filename, $processId, ExecutionInstanceInterface $instance, TokenInterface $token)
    {
        parent::__construct($filename, $processId, $instance);
        $this->tokenId = $token->getId();
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
            
            $token = null;
            foreach($instance->getTokens() as $token) {
                if ($token->getId() === $this->tokenId) {
                    break;
                }
            }

            //Do the action
            App::call([$this, 'action'], compact('workflow', 'process', 'instance', 'token'));

            //Run engine to the next state
            $workflow->getEngine()->runToNextState();
        } catch (\Throwable $t) {
            dd($t);
        }
    }
}
