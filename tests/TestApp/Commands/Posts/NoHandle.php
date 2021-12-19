<?php

namespace TestApp\Commands\Posts;

use LaravelCode\EventSouring\Commands\Storable;

class NoHandle extends AbstractCommand
{
    use Storable;

    public function __construct(
        public string $id
    ) {
    }
}
