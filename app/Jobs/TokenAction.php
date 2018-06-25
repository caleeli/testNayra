<?php
namespace App\Jobs;

use ProcessMaker\Nayra\Contracts\Storage\BpmnDocumentInterface;
use ProcessMaker\Nayra\Contracts\Engine\ExecutionInstanceInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\TokenInterface;
use Illuminate\Support\Facades\App;

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
        $this->tokenId = $token->uid;
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
            if (!$instance) {
                return;
            }
            
            $token = null;
            $element = null;
            foreach($instance->getTokens() as $token) {
                if ($token->getId() === $this->tokenId) {
                    $element = $workflow->getElementInstanceById($token->getProperty('element_ref'));
                    break;
                } else {
                    $token = null;
                }
            }
            $activity = $element;

            //Do the action
            App::call([$this, 'action'], compact('workflow', 'process', 'instance', 'token', 'element', 'activity'));

            //Run engine to the next state
            $workflow->getEngine()->runToNextState();
        } catch (\Throwable $t) {
            dd($t);
        }
    }
}
