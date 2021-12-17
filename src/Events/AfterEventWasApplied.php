<?php

namespace LaravelCode\EventSouring\Events;

use Illuminate\Database\Eloquent\Model;
use LaravelCode\EventSouring\Contracts\Event\Event;
use LaravelCode\EventSouring\Helpers\JsonSerializeTrait;

class AfterEventWasApplied implements \JsonSerializable
{
    use JsonSerializeTrait;

    public function __construct(public Event $event, public ?Model $model = null)
    {
    }
}
