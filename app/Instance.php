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

    use ExecutionInstanceTrait;

    protected $fillable = [
        'uid',
    ];

    protected $attributes = [
        'uid'=>null,
    ];

    public function __construct(array $argument=[])
    {
        parent::__construct($argument);
        $this->bootElement([]);
        $this->setId(uniqid());
    }

    public function tokens()
    {
        return $this->hasMany(\App\Token::class);
    }

    public function process()
    {
        return $this->belongsTo(\App\Process::class);
    }
}
