<?php

namespace TestApp\Events\Posts;

class StatusWasChanged extends AbstractEvent
{
    public function __construct(
        public string $id,
        public string $status,
    ) {
    }
}
