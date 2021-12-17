<?php

namespace LaravelCode\EventSouring\Events;

use LaravelCode\EventSouring\Helpers\JsonSerializeTrait;

class BeforeEventIsApplied
{
    use JsonSerializeTrait;

    public function __construct(public $event)
    {
    }
}
