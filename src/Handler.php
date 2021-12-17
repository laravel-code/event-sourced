<?php

namespace LaravelCode\EventSouring;

interface Handler
{
    public function handle(string $eventName, array $events): void;
}
