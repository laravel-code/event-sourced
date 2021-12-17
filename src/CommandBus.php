<?php

namespace LaravelCode\EventSouring;

use Illuminate\Support\Facades\Event;
use LaravelCode\EventSouring\Error\StopPropagation;

class CommandBus
{
    private array $commandBus;

    public function __construct(array $commandBus)
    {
        $this->commandBus = $commandBus;
        Event::listen('*', $this->handle());
    }

    private function handle(): \Closure
    {
        return function ($eventName, $events) {
            foreach ($this->commandBus as $bus) {
                try {
                    $bus->handle($eventName, $events);
                } catch (StopPropagation) {
                    return false;
                }
            }
        };
    }
}
