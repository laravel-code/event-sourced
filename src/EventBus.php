<?php

namespace LaravelCode\EventSouring;

use Illuminate\Support\Facades\Event;
use LaravelCode\EventSouring\Error\StopPropagation;

class EventBus
{
    private array $eventBus;

    public function __construct(array $eventBus)
    {
        $this->eventBus = $eventBus;
        Event::listen('*', $this->handle());
    }

    private function handle(): \Closure
    {
        return function ($eventName, $events) {
            foreach ($this->eventBus as $bus) {
                try {
                    $bus->handle($eventName, $events);
                } catch (StopPropagation) {
                    return;
                }
            }
        };
    }
}
