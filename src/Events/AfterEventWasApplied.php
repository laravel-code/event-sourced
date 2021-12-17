<?php

namespace LaravelCode\EventSouring\Events;

use Illuminate\Database\Eloquent\Model;
use LaravelCode\EventSouring\Helpers\JsonSerializeTrait;

class AfterEventWasApplied implements \JsonSerializable
{
    use JsonSerializeTrait;

    public function __construct(public $event, public ?Model $model = null)
    {
    }
}
