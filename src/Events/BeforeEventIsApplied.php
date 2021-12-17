<?php

namespace LaravelCode\EventSouring\Events;

use LaravelCode\EventSouring\Contracts\Event\Event;
use LaravelCode\EventSouring\Helpers\JsonSerializeTrait;

class BeforeEventIsApplied
{
    use JsonSerializeTrait;

    public function __construct(public Event $event)
    {
    }
}
