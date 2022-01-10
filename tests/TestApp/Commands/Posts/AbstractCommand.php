<?php

namespace TestApp\Commands\Posts;

use LaravelCode\EventSourcing\Commands\Storable;
use LaravelCode\EventSourcing\Contracts\Command\Command;
use LaravelCode\EventSourcing\Contracts\Command\ShouldStore;

class AbstractCommand implements Command, \JsonSerializable, ShouldStore
{
    use Storable;
}
