<?php

namespace App\Jobs;

use ProcessMaker\Nayra\Contracts\Bpmn\TokenInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\ActivityInterface;

class CompleteActivity extends TokenAction
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function action(TokenInterface $token, ActivityInterface $activity)
    {
        $activity->complete($token);
    }
}
