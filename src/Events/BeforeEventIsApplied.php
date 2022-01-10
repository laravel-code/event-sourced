<?php

namespace LaravelCode\EventSourcing\Events;

use LaravelCode\EventSourcing\Contracts\Event\Event;
use LaravelCode\EventSourcing\Helpers\JsonSerializeTrait;

class BeforeEventIsApplied implements \JsonSerializable, Event
{
    use JsonSerializeTrait, Storable;

    public function __construct(public Event $event)
    {
    }
}
