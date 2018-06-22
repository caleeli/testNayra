<?php

namespace App\Jobs;

use ProcessMaker\Nayra\Contracts\Bpmn\TokenInterface;


class CompleteActivity extends TokenAction
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function action(TokenInterface $token)
    {
        dd($token->getId());
    }
}
