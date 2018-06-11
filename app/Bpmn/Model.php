<?php

namespace App\Bpmn;

use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * Description of Model
 *
 */
class Model extends EloquentModel
{

    public function dump()
    {
        $dump = [];
        foreach(static::MAP as $property => $attribute) {
            $dump[$property] = $this->$attribute;
        }
        //$state = $this->getFactory()->loadBpmElementById($this->element_ref)->getState($this->status);
        //$dump['owner'] = $state;
        return $dump;
    }

    public function loadFromEloquent()
    {
        $properties = [];
        foreach(static::MAP as $property => $attribute) {
            $properties[$property] = $this->$attribute;
        }
        $this->setProperties($properties);
        return $this;
    }

}
