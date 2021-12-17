<?php

namespace TestApp\Events\Posts;

use LaravelCode\EventSouring\Contracts\Event\Event;
use LaravelCode\EventSouring\Contracts\Event\ShouldStore;
use LaravelCode\EventSouring\Events\Storable;

class AbstractEvent implements Event, \JsonSerializable, ShouldStore
{
    use Storable;
}
