<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use ProcessMaker\Nayra\Contracts\Storage\BpmnDocumentInterface;

class CallProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
    public function handle()
    {
        /* @var $bpmn \ProcessMaker\Nayra\Storage\BpmnDocument */
        $bpmn = resolve(BpmnDocumentInterface::class);
        //Load the process definition
        $bpmn->load($this->filename);
        //$bpmn->loadXML('<definitions>...</definitions>');

        //Get the reference to the object
        $process = $bpmn->getProcess($this->processId);
        //Do the action: Start a process
        $process->call();

        //Run engine to the next state
        $bpmn->getEngine()->runToNextState();
    }
}
