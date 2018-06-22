<?php

namespace App\Repositories;

use App\Instance;
use ProcessMaker\Nayra\Bpmn\RepositoryTrait;
use ProcessMaker\Nayra\Contracts\Bpmn\DataStoreInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\TokenInterface;
use ProcessMaker\Nayra\Contracts\Engine\ExecutionInstanceInterface;
use ProcessMaker\Nayra\Contracts\Repositories\ExecutionInstanceRepositoryInterface;
use ProcessMaker\Nayra\Contracts\Repositories\ProcessRepositoryInterface;

/**
 * Execution Instance Repository.
 *
 * @package ProcessMaker\Models
 */
class ExecutionInstanceRepository implements ExecutionInstanceRepositoryInterface
{
    use RepositoryTrait;

    /**
     * Array to simulate a storage of execution instances.
     *
     * @var array $data
     */
    private static $data = [];

    /**
     * Create an execution instance.
     *
     * @return \App\Instancex
     */
    public function createExecutionInstance()
    {
        dd(':o');
        return $this->getStorage()->getFactory()->createInstanceOf(ExecutionInstanceInterface::class);
    }

    /**
     * Load an execution instance from a persistent storage.
     *
     * @param string $uid
     *
     * @return \ProcessMaker\Nayra\Contracts\Engine\ExecutionInstanceInterface
     */
    public function loadExecutionInstanceByUid($uid)
    {
        $instance = Instance::where('uid', $uid)->first();
        $processId = $instance->process->uid;
        $processRepository = $this->getStorage()->getFactory()->createInstanceOf(ProcessRepositoryInterface::class, $this->getStorage());
        $process = $processRepository->loadProcessByUid($processId);
        $dataStore = $this->getStorage()->getFactory()->createInstanceOf(DataStoreInterface::class);
        $dataStore->setData($instance->data);
        $instance->setProcess($process);
        $instance->setDataStore($dataStore);
        $process->getTransitions($this->getStorage()->getFactory());

        //Load tokens:
        foreach($instance->tokens as $tokenInstance) {
            $tokenInfo = [
                'id' => $tokenInstance->uid,
                'status' => $tokenInstance->status,
                'element_ref' => $tokenInstance->element_ref,
            ];
            $token = $this->getStorage()->getFactory()->createInstanceOf(TokenInterface::class);
            $token->setProperties($tokenInfo);
            $element = $this->getStorage()->getElementInstanceById($tokenInfo['element_ref']);
            $element->addToken($instance, $token);
        }
        return $instance;
    }

    /**
     * Create or update an execution instance to a persistent storage.
     *
     * @param \ProcessMaker\Nayra\Contracts\Engine\ExecutionInstanceInterface $instance
     *
     * @return $this
     */
    public function storeExecutionInstance(ExecutionInstanceInterface $instance)
    {
        // TODO: Implement store() method.
    }
}
