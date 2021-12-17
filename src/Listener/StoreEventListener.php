<?php

namespace LaravelCode\EventSouring\Listener;

use Illuminate\Support\Facades\Log;
use LaravelCode\EventSouring\Contracts\Event\ShouldStore;
use LaravelCode\EventSouring\Handler;
use LaravelCode\EventSouring\Inflector\ApplyInflector;
use LaravelCode\EventSouring\Locator\InMemoryLocator;

class StoreEventListener extends EventListener implements Handler
{
    public function __construct()
    {
        $listener = new \LaravelCode\EventSouring\Models\Listeners\StoreEventListener();
        $locations = ['*' => $listener];
        $locator = new InMemoryLocator($locations);

        parent::__construct($locator, new ApplyInflector());
    }

    public function handle($eventName, $events)
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
            if (!$event instanceof ShouldStore) {
                continue;
            }

            if ($event->isBeingReplayed()) {
                Log::notice(sprintf('The events %s is being replayed, do not store', get_class($event)));

                continue;
            }

            call_user_func([$handler, $name], $event);
        }
    }
}
