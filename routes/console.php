<?php

use App\BpmnEngine;
use App\EventBus;
use Illuminate\Support\Facades\Event;
use ProcessMaker\Models\CallActivity;
use ProcessMaker\Models\ExecutionInstanceRepository;
use ProcessMaker\Models\ProcessRepository;
use ProcessMaker\Models\TokenRepository;
use ProcessMaker\Nayra\Bpmn\Lane;
use ProcessMaker\Nayra\Bpmn\LaneSet;
use ProcessMaker\Nayra\Bpmn\Models\Activity;
use ProcessMaker\Nayra\Bpmn\Models\ActivityActivatedEvent;
use ProcessMaker\Nayra\Bpmn\Models\DataStore;
use ProcessMaker\Nayra\Bpmn\Models\EndEvent;
use ProcessMaker\Nayra\Bpmn\Models\ExclusiveGateway;
use ProcessMaker\Nayra\Bpmn\Models\Flow;
use ProcessMaker\Nayra\Bpmn\Models\InclusiveGateway;
use ProcessMaker\Nayra\Bpmn\Models\ParallelGateway;
use ProcessMaker\Nayra\Bpmn\Models\Process;
use ProcessMaker\Nayra\Bpmn\Models\ScriptTask;
use ProcessMaker\Nayra\Bpmn\Models\StartEvent;
use ProcessMaker\Nayra\Bpmn\Models\Token;
use ProcessMaker\Nayra\Bpmn\Models\Collaboration;
use ProcessMaker\Nayra\Bpmn\Models\ConditionalEventDefinition;
use ProcessMaker\Nayra\Bpmn\Models\DataInput;
use ProcessMaker\Nayra\Bpmn\Models\DataOutput;
use ProcessMaker\Nayra\Bpmn\Models\Error;
use ProcessMaker\Nayra\Bpmn\Models\ErrorEventDefinition;
use ProcessMaker\Nayra\Bpmn\Models\InputSet;
use ProcessMaker\Nayra\Bpmn\Models\IntermediateCatchEvent;
use ProcessMaker\Nayra\Bpmn\Models\IntermediateThrowEvent;
use ProcessMaker\Nayra\Bpmn\Models\ItemDefinition;
use ProcessMaker\Nayra\Bpmn\Models\Message;
use ProcessMaker\Nayra\Bpmn\Models\MessageEventDefinition;
use ProcessMaker\Nayra\Bpmn\Models\MessageFlow;
use ProcessMaker\Nayra\Bpmn\Models\Operation;
use ProcessMaker\Nayra\Bpmn\Models\OutputSet;
use ProcessMaker\Nayra\Bpmn\Models\Participant;
use ProcessMaker\Nayra\Bpmn\Models\Signal;
use ProcessMaker\Nayra\Bpmn\Models\SignalEventDefinition;
use ProcessMaker\Nayra\Bpmn\Models\TerminateEventDefinition;
use ProcessMaker\Nayra\Bpmn\Models\TimerEventDefinition;
use ProcessMaker\Nayra\Bpmn\State;
use ProcessMaker\Nayra\Contracts\Bpmn\ActivityInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\CallActivityInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\CollaborationInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\ConditionalEventDefinitionInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\DataInputInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\DataOutputInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\DataStoreInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\EndEventInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\ErrorEventDefinitionInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\ErrorInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\ExclusiveGatewayInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\FlowInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\FormalExpressionInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\InclusiveGatewayInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\InputSetInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\IntermediateCatchEventInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\IntermediateThrowEventInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\ItemDefinitionInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\LaneInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\LaneSetInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\MessageEventDefinitionInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\MessageFlowInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\MessageInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\OperationInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\OutputSetInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\ParallelGatewayInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\ParticipantInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\ProcessInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\ScriptTaskInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\SignalEventDefinitionInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\SignalInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\StartEventInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\StateInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\TerminateEventDefinitionInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\TimerEventDefinitionInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\TokenInterface;
use ProcessMaker\Nayra\Contracts\Engine\ExecutionInstanceInterface;
use ProcessMaker\Nayra\Contracts\Repositories\ExecutionInstanceRepositoryInterface;
use ProcessMaker\Nayra\Contracts\Repositories\ProcessRepositoryInterface;
use ProcessMaker\Nayra\Contracts\Repositories\TokenRepositoryInterface;
use ProcessMaker\Nayra\Engine\ExecutionInstance;
use ProcessMaker\Nayra\Factory;
use ProcessMaker\Nayra\Storage\BpmnDocument;
use ProcessMaker\Test\FormalExpression;

/*
  |--------------------------------------------------------------------------
  | Console Routes
  |--------------------------------------------------------------------------
  |
  | This file is where you may define all of your Closure based console
  | commands. Each Closure is bound to a command instance allowing a
  | simple approach to interacting with each command's IO methods.
  |
 */

Artisan::command('bpmn', function () {

    $bpmnRepository = bootBpmnRepository();

    //Get the process object (REQUIRES Load the BPMN file)
    $process = $bpmnRepository->getProcess('PROCESS_1');

    //Start a process (REQUIRES a process reference)
    $process->call();
    $bpmnRepository->getEngine()->runToNextState();

})->describe('Run BPMN');

function bootNayra()
{
    //Initialize the EventBus with the Laravel events dispatcher
    $eventBus = new EventBus(app('events'));

    //Register BPMN Events
    registerBpmnEvents();

    //Initialize Factory
    $factory = buildFactory();

    //Initialize the BpmnEngine (REQUIRES $factory $busEvents)
    $engine = new BpmnEngine($factory, $eventBus);

    return $engine;
}

function bootBpmnRepository() {
    $engine = bootNayra();
    $factory = $engine->getFactory();

    //Initialize BpmnDocument repository (REQUIRES $engine $factory)
    $bpmnRepository = new BpmnDocument();
    $bpmnRepository->setEngine($engine);
    $bpmnRepository->setFactory($factory);

    //Load the BPMN file
    $bpmnRepository->load('bpmn/Lanes.bpmn');

    return $bpmnRepository;
}

function registerBpmnEvents()
{
    Event::listen(ActivityInterface::EVENT_ACTIVITY_ACTIVATED, [EventBus::class, 'listenAAE'] );
}


function buildFactory()
{

    $mappings = [
        ActivityInterface::class                    => Activity::class,
        CallActivityInterface::class                => CallActivity::class,
        CollaborationInterface::class               => Collaboration::class,
        ConditionalEventDefinitionInterface::class  => ConditionalEventDefinition::class,
        DataStoreInterface::class                   => DataStore::class,
        EndEventInterface::class                    => EndEvent::class,
        ErrorEventDefinitionInterface::class        => ErrorEventDefinition::class,
        ErrorInterface::class                       => Error::class,
        ExclusiveGatewayInterface::class            => ExclusiveGateway::class,
        ExecutionInstanceInterface::class           => ExecutionInstance::class,
        ExecutionInstanceRepositoryInterface::class => ExecutionInstanceRepository::class,
        FlowInterface::class                        => Flow::class,
        FormalExpressionInterface::class            => FormalExpression::class,
        InclusiveGatewayInterface::class            => InclusiveGateway::class,
        IntermediateCatchEventInterface::class      => IntermediateCatchEvent::class,
        IntermediateThrowEventInterface::class      => IntermediateThrowEvent::class,
        ItemDefinitionInterface::class              => ItemDefinition::class,
        LaneInterface::class                        => Lane::class,
        LaneSetInterface::class                     => LaneSet::class,
        MessageEventDefinitionInterface::class      => MessageEventDefinition::class,
        MessageFlowInterface::class                 => MessageFlow::class,
        MessageInterface::class                     => Message::class,
        OperationInterface::class                   => Operation::class,
        ParallelGatewayInterface::class             => ParallelGateway::class,
        ParticipantInterface::class                 => Participant::class,
        ProcessInterface::class                     => Process::class,
        ProcessRepositoryInterface::class           => ProcessRepository::class,
        ScriptTaskInterface::class                  => ScriptTask::class,
        SignalEventDefinitionInterface::class       => SignalEventDefinition::class,
        SignalInterface::class                      => Signal::class,
        StartEventInterface::class                  => StartEvent::class,
        StateInterface::class                       => State::class,
        TerminateEventDefinitionInterface::class    => TerminateEventDefinition::class,
        TimerEventDefinitionInterface::class        => TimerEventDefinition::class,
        TokenInterface::class                       => Token::class,
        TokenRepositoryInterface::class             => TokenRepository::class,
        DataInputInterface::class                   => DataInput::class,
        DataOutputInterface::class                  => DataOutput::class,
        InputSetInterface::class                    => InputSet::class,
        OutputSetInterface::class                   => OutputSet::class,
    ];
    return new Factory($mappings);
}
