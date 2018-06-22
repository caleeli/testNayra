<?php
namespace App\Jobs;

use ProcessMaker\Nayra\Contracts\Bpmn\ProcessInterface;

class CallProcess extends ProcessAction
{

    /**
     * Execute the job.
     *
     * @return void
     */
    public function action(ProcessInterface $process)
    {
        $process->call();
    }
}
