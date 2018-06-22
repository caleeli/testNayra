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

abstract class ProcessAction implements ShouldQueue
{

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    public $filename;
    public $processId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filename, $processId)
    {
        $this->filename = $filename;
        $this->processId = $processId;
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
