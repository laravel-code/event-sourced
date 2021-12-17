<?php

namespace TestApp\Commands\Posts;

use LaravelCode\EventSouring\Commands\Storable;
use LaravelCode\EventSouring\Contracts\Command\Command;
use LaravelCode\EventSouring\Contracts\Command\ShouldStore;

class AbstractCommand implements Command, \JsonSerializable, ShouldStore
{
    use Storable;
}
