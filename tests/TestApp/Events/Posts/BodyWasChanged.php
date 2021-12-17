<?php

namespace TestApp\Events\Posts;

class BodyWasChanged extends AbstractEvent
{
    public function __construct(
        public string $id,
        public string $body,
    ) {
    }
}
