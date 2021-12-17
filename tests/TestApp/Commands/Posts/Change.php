<?php

namespace TestApp\Commands\Posts;

use LaravelCode\EventSouring\Commands\Storable;

class Change extends AbstractCommand
{
    use Storable;

    public function __construct(
        public string $id,
        public string $title,
        public string $body,
        public string $status
    ) {
    }
}
