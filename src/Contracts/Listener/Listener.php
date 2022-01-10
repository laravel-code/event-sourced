<?php

namespace LaravelCode\EventSourcing\Contracts\Listener;

interface Listener
{
    public function handle(string $eventName, array $events): void;
}
