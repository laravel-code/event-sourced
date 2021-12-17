<?php

namespace LaravelCode\EventSouring\Contracts\Listener;

interface Listener
{
    public function handle(string $eventName, array $events): void;
}
