<?php

namespace TestApp\Events\Posts;

use LaravelCode\EventSourcing\Contracts\Event\Event;
use LaravelCode\EventSourcing\Contracts\Event\ShouldStore;
use LaravelCode\EventSourcing\Events\Storable;

class AbstractEvent implements Event, \JsonSerializable, ShouldStore
{
    use Storable;
}
