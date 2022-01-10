<?php

namespace LaravelCode\EventSourcing;

interface Handler
{
    public function handle(string $eventName, array $events): void;
}
