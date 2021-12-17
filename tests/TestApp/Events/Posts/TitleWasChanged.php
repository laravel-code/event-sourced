<?php

namespace TestApp\Events\Posts;

class TitleWasChanged extends AbstractEvent
{
    public function __construct(
        public string $id,
        public string $title,
    ) {
    }
}
