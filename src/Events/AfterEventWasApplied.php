<?php

namespace LaravelCode\EventSourcing\Events;

use Illuminate\Database\Eloquent\Model;
use LaravelCode\EventSourcing\Contracts\Event\Event;
use LaravelCode\EventSourcing\Helpers\JsonSerializeTrait;

class AfterEventWasApplied implements \JsonSerializable, Event
{
    use JsonSerializeTrait;
    use Storable;

    public function __construct(public Event $event, public ?Model $model = null)
    {
    }
}
