<?php

namespace LaravelCode\EventSouring\Contracts\Locator;

interface Locator
{
    public function execute(string $eventName, array $events): mixed;
}
