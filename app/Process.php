<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use ProcessMaker\Nayra\Contracts\Storage\BpmnDocumentInterface;

class Process extends Model
{

    private $bpmnDefinitions;
    protected $fillable = [
        'uid',
        'bpmn',
    ];
    protected $attributes = [
        'uid' => null,
        'bpmn' => null,
    ];

    public function getDefinitions()
    {
        if (empty($this->bpmnDefinitions)) {
            $this->bpmnDefinitions = app(BpmnDocumentInterface::class);
            $this->bpmnDefinitions->loadXML($this->bpmn);
        }
        return $this->bpmnDefinitions;
    }
}
