<?php

namespace LaravelCode\EventSourcing\Events\Listeners;

use Illuminate\Support\Facades\Log;
use LaravelCode\EventSourcing\Contracts\Event\ShouldStore;
use LaravelCode\EventSourcing\Handler;
use LaravelCode\EventSourcing\Inflector\ApplyInflector;
use LaravelCode\EventSourcing\Locator\InMemoryLocator;

class StoreEventListener extends EventListener implements Handler
{
    public function __construct()
    {
        $listener = new StoreEventDatabaseListener();
        $locations = ['*' => $listener];
        $locator = new InMemoryLocator($locations);

        parent::__construct($locator, new ApplyInflector());
    }

    public function handle(string $eventName, array $events): void
    {
        $handler = $this->locator->execute($eventName, $events);

        if (!$handler) {
            return;
        }

        $name = $this->inflector->execute($eventName);

        if (!is_callable([$handler, $name])) {
            Log::error(sprintf('Could not call %s on listener %s', $name, get_class($handler)));

            return;
        }
        /** @var ShouldStore $event */
        foreach ($events as $event) {
            $this->processEvent($event, $handler, $name);
        }
    }

    private function processEvent(mixed $event, mixed $handler, string $name): void
    {
        if (!$event instanceof ShouldStore) {
            return;
        }

        if ($event->isBeingReplayed()) {
            Log::notice(sprintf('The events %s is being replayed, do not store', get_class($event)));

            return;
        }

        call_user_func([$handler, $name], $event);
    }
}
