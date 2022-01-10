<?php

namespace LaravelCode\EventSourcing\Contracts\Locator;

interface Locator
{
    public function execute(string $eventName, array $events): mixed;
}
