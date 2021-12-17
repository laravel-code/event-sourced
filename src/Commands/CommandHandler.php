<?php

namespace LaravelCode\EventSouring\Commands;

use Illuminate\Support\Facades\Log;
use LaravelCode\EventSouring\Contracts\Locator\Locator;
use LaravelCode\EventSouring\Events\AfterCommandWasHandled;
use LaravelCode\EventSouring\Events\BeforeCommandIsHandled;
use LaravelCode\EventSouring\Handler;
use LaravelCode\EventSouring\Inflector\Inflector;

class CommandHandler implements Handler
{
    public function __construct(
        private Locator $locator,
        private Inflector $inflector,
        private bool $withEvents = true
    ) {
    }

    /**
     * @param $eventName
     * @param $events
     * @return void
     */
    public function handle($eventName, $events)
    {
        $handler = $this->locator->execute($eventName, $events);
        if (!$handler) {
            return;
        }

        $name = $this->inflector->execute($eventName);

        if (!is_callable([$handler, $name])) {
            Log::error(sprintf('Could not call %s on handler %s', $name, get_class($handler)));

            throw new \BadFunctionCallException(
                sprintf('Could not call %s on handler %s', $name, get_class($handler))
            );
        }

        foreach ($events as $event) {
            $this->emit(new BeforeCommandIsHandled($event));

            call_user_func([$handler, $name], $event);

            $this->emit(new AfterCommandWasHandled($event));
        }
    }

    private function emit(mixed $event)
    {
        if ($this->withEvents) {
            event($event);
        }
    }
}
