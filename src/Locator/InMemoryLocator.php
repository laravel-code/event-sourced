<?php

namespace LaravelCode\EventSouring\Locator;

use LaravelCode\EventSouring\Contracts\Locator\Locator;

class InMemoryLocator implements Locator
{
    private array $listeners;

    public function __construct(array $listeners)
    {
        $this->listeners = $listeners;
    }

    public function execute(string $eventName, array $events): mixed
    {
        foreach ($this->listeners as $listenEvent => $handler) {
            if ($this->shouldHandle($eventName, $listenEvent)) {
                return $handler;
            }
        }

        return null;
    }

    private function shouldHandle($eventName, $listenEvent): bool
    {
        if (!class_exists($eventName)) {
            return false;
        }

        if ($listenEvent === '*') {
            return true;
        }

        if ($pos = strpos($listenEvent, '*')) {
            return substr($listenEvent, 0, $pos) === substr($eventName, 0, $pos);
        }

        return $eventName === $listenEvent;
    }
}
