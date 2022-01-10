<?php

namespace LaravelCode\EventSourcing\Events;

use LaravelCode\EventSourcing\Contracts\Command\Command;
use LaravelCode\EventSourcing\Contracts\Event\Event;
use LaravelCode\EventSourcing\Helpers\JsonSerializeTrait;

class BeforeCommandIsHandled implements \JsonSerializable, Event
{
    use JsonSerializeTrait, Storable;

    public function __construct(public Command $command)
    {
    }
}
