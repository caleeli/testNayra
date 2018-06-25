<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use ProcessMaker\Nayra\Contracts\Storage\BpmnDocumentInterface;
use ProcessMaker\Nayra\Storage\BpmnDocument;
use ProcessMaker\Nayra\Contracts\Bpmn\ProcessInterface;
use Illuminate\Support\Facades\App;
use App\Process as Definitions;

abstract class ProcessAction implements ShouldQueue
{

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    public $definitionsId;
    public $processId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Definitions $definitions, ProcessInterface $process)
    {
        $this->definitionsId = $definitions->id;
        $this->processId = $process->getId();
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

            //Get the reference to the object
            $process = $workflow->getProcess($this->processId);

            //Do the action
            App::call([$this, 'action'], compact('workflow', 'process'));

            //Run engine to the next state
            $workflow->getEngine()->runToNextState();
        } catch (\Throwable $t) {
            dd($t);
        }
    }
}
