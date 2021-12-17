<?php

namespace LaravelCode\EventSouring\Events;

use LaravelCode\EventSouring\Contracts\Command\Command;
use LaravelCode\EventSouring\Helpers\JsonSerializeTrait;

class AfterCommandWasHandled implements \JsonSerializable
{
    use JsonSerializeTrait;

    public function __construct(public Command $command)
    {
    }
}
