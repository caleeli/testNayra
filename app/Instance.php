<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use ProcessMaker\Nayra\Engine\ExecutionInstanceTrait;
use ProcessMaker\Nayra\Contracts\Engine\ExecutionInstanceInterface;

/**
 * Description of Instance
 *
 */
class Instance extends Model implements ExecutionInstanceInterface
{

    use ExecutionInstanceTrait {
        setProperty as setBPMNProperty;
    }
    const MAP = [
        'id'         => 'uid',
        'processRef' => 'process_ref',
    ];

    public function __construct(array $argument=[])
    {
        parent::__construct($argument);
        $this->bootElement([]);
        $this->setId(uniqid());
    }

    public function save(array $options = array())
    {
        $dump = $this->dump();
        foreach ($dump['properties'] as $name => $value) {
            $attr = static::MAP[$name];
            $this->$attr = $value;
        }
        parent::save($options);
        $activeTokens = [];
        foreach ($this->getTokens() as $token) {
            $element = $token->getOwner()->getOwner();
            if (!($element instanceof \ProcessMaker\Nayra\Contracts\Bpmn\ActivityInterface)) {
                //continue;
            }
            $token->uid = $token->getId();
            $token->status = $token->getStatus();
            $token->element_ref = $element->getId();
            $token->instance_id = $this->id;
            dump($token->toArray());
            $token->save();
            $activeTokens[] = $token->id;
        }
        $this->tokens()
            ->whereNotIn('id', $activeTokens)
            ->update(['status'=>\ProcessMaker\Nayra\Contracts\Bpmn\ActivityInterface::TOKEN_STATE_CLOSED]);
    }

    public function tokens()
    {
        return $this->hasMany(\ProcessMaker\Models\Token::class);
    }

    public function loadTokens()
    {
        $props = [];
        foreach (static::MAP as $property => $attribute) {
            $props[$property] = $this->$attribute;
        }
        $this->setProperties($props);
        $tokens = $this->tokens()->where('status', '!=', \ProcessMaker\Nayra\Contracts\Bpmn\ActivityInterface::TOKEN_STATE_CLOSED)->get();
        $process = $this->getFactory()->loadBpmElementById($this->process_ref);
        $tokensByElement = [];
        foreach ($tokens as $token) {
            $token->loadFromEloquent();
            $elementId = $token->element_ref;
            $token->setFactory($this->getFactory());
            $tokensByElement[$elementId][] = $token;
        }
        foreach($tokensByElement as $elementId => $tokens) {
            $element = $this->getFactory()->loadBpmElementById($elementId);
            $element->loadTokens($this, $tokens);
        }
    }

}
