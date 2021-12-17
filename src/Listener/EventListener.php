<?php

namespace LaravelCode\EventSouring\Listener;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use LaravelCode\EventSouring\Contracts\Event\Event;
use LaravelCode\EventSouring\Contracts\Listener\Listener;
use LaravelCode\EventSouring\Contracts\Locator\Locator;
use LaravelCode\EventSouring\Events\AfterEventWasApplied;
use LaravelCode\EventSouring\Events\BeforeEventIsApplied;
use LaravelCode\EventSouring\Events\EntityWasSet;
use LaravelCode\EventSouring\Inflector\Inflector;

class EventListener implements Listener
{
    public function __construct(
        protected Locator $locator,
        protected Inflector $inflector,
    ) {
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

        /** @var Event $event */
        foreach ($events as $event) {
            $entity = null;

            event(new BeforeEventIsApplied($event));

            /** @var Model|null $entity */
            $entity = call_user_func([$handler, $name], $event);

            if ($entity) {
                event(new EntityWasSet($entity));
            }

            event(new AfterEventWasApplied($event, $entity));
        }
    }
}
