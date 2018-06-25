<?php
namespace App\Jobs;

use ProcessMaker\Nayra\Contracts\Engine\ExecutionInstanceInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\TokenInterface;
use Illuminate\Support\Facades\App;
use App\Process as Definitions;

abstract class TokenAction extends ProcessAction
{

    public $definitionsId;
    public $instanceId;
    public $tokenId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Definitions $definitions, ExecutionInstanceInterface $instance, TokenInterface $token)
    {
        $this->definitionsId = $definitions->id;
        $this->instanceId = $instance->uid;
        $this->tokenId = $token->uid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            //Load the process definition
            $definitions = Definitions::find($this->definitionsId);
            $workflow = $definitions->getDefinitions();

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
            App::call([$this, 'action'], compact('workflow', 'instance', 'token', 'element', 'activity'));

            //Run engine to the next state
            $workflow->getEngine()->runToNextState();
        } catch (\Throwable $t) {
            dd($t);
        }
    }
}
