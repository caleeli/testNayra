<?php

namespace App;

use ProcessMaker\Nayra\Factory;
use ProcessMaker\Models\CallActivity;
use ProcessMaker\Models\ExecutionInstanceRepository;
use ProcessMaker\Models\ProcessRepository;
use ProcessMaker\Models\TokenRepository;
use ProcessMaker\Nayra\Bpmn\Lane;
use ProcessMaker\Nayra\Bpmn\LaneSet;
use ProcessMaker\Nayra\Bpmn\Models\Activity;
use ProcessMaker\Nayra\Bpmn\Models\DataStore;
use ProcessMaker\Nayra\Bpmn\Models\EndEvent;
use ProcessMaker\Nayra\Bpmn\Models\ExclusiveGateway;
use ProcessMaker\Nayra\Bpmn\Models\Flow;
use ProcessMaker\Nayra\Bpmn\Models\InclusiveGateway;
use ProcessMaker\Nayra\Bpmn\Models\ParallelGateway;
use ProcessMaker\Nayra\Bpmn\Models\Process;
use ProcessMaker\Nayra\Bpmn\Models\ScriptTask;
use ProcessMaker\Nayra\Bpmn\Models\StartEvent;
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
use ProcessMaker\Test\FormalExpression;
use App\Token;
use App\Instance;

/**
 * Description of NayraFactory
 *
 */
class BpmnFactory extends Factory
{
    const mapping = [
        ActivityInterface::class                    => Activity::class,
        CallActivityInterface::class                => CallActivity::class,
        CollaborationInterface::class               => Collaboration::class,
        ConditionalEventDefinitionInterface::class  => ConditionalEventDefinition::class,
        DataStoreInterface::class                   => DataStore::class,
        EndEventInterface::class                    => EndEvent::class,
        ErrorEventDefinitionInterface::class        => ErrorEventDefinition::class,
        ErrorInterface::class                       => Error::class,
        ExclusiveGatewayInterface::class            => ExclusiveGateway::class,
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
        TokenRepositoryInterface::class             => TokenRepository::class,
        DataInputInterface::class                   => DataInput::class,
        DataOutputInterface::class                  => DataOutput::class,
        InputSetInterface::class                    => InputSet::class,
        OutputSetInterface::class                   => OutputSet::class,
        //Custom:
        ExecutionInstanceRepositoryInterface::class => ExecutionInstanceRepository::class,
        ExecutionInstanceInterface::class           => Instance::class,
        TokenInterface::class                       => Token::class,
    ];

}
