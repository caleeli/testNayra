<?php
namespace App\Repositories;

use App\Instance;
use App\Repositories\ExecutionInstanceRepository;
use App\Token;
use ProcessMaker\Nayra\Contracts\Repositories\StorageInterface;
use ProcessMaker\Nayra\Contracts\RepositoryInterface;
use ProcessMaker\Nayra\DefinitionsRepositoryTrait;

/**
 * Definitions Repository
 *
 */
class DefinitionsRepository implements RepositoryInterface
{

    use DefinitionsRepositoryTrait;

    public function createExecutionInstance()
    {
        return new Instance();
    }

    public function createToken()
    {
        return new Token();
    }

    public function createCallActivity()
    {
        
    }

    public function createExecutionInstanceRepository(StorageInterface $storage)
    {
        return new ExecutionInstanceRepository($storage);
    }

    public function createFormalExpression()
    {
        
    }
}
