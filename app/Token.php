<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use ProcessMaker\Nayra\Bpmn\TokenTrait;
use ProcessMaker\Nayra\Contracts\Bpmn\TokenInterface;

/**
 * Token implementation.
 *
 * @package ProcessMaker\Nayra\Bpmn
 */
class Token extends Model implements TokenInterface
{
    use TokenTrait;

    const PROPERTY_STATUS = 'status';

    const MAP = [
            'id' => 'uid',
            'status' => 'status',
            'elementRef' => 'element_ref',
            'instance_id' => 'instance_id',
        ];

    protected $fillable = [
        'uid',
        'status',
        'element_ref',
    ];

    protected $attributes = [
        'uid'=>null,
        'status'=>null,
        'element_ref'=>null,
        'instance_id'=>null,
    ];

    /**
     * Base Model constructor.
     *
     * @param array $properties
     */
    public function __construct(array $arguments=[])
    {
        parent::__construct($arguments);
        $this->bootElement([
            $this->instance()
        ]);
        $this->setId(uniqid());
    }

    public function instance()
    {
        return $this->belongsTo(\App\Instance::class);
    }
}
