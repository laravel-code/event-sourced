<?php

namespace LaravelCode\EventSourcing\Events;

use LaravelCode\EventSourcing\Contracts\Command\Command;
use LaravelCode\EventSourcing\Contracts\Event\Event;
use LaravelCode\EventSourcing\Helpers\JsonSerializeTrait;

class AfterCommandWasHandled implements \JsonSerializable, Event
{
    use JsonSerializeTrait;
    use Storable;

    public function __construct(public Command $command)
    {
    }
}
