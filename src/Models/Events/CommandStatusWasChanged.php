<?php

namespace LaravelCode\EventSourcing\Models\Events;

use LaravelCode\EventSourcing\Contracts\Event\Event;
use LaravelCode\EventSourcing\Events\Storable;

class CommandStatusWasChanged implements Event
{
    use Storable;

    public function __construct(public string $id, public string $status)
    {
    }
}
