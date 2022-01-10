<?php

namespace LaravelCode\EventSourcing\Commands;

use Illuminate\Support\Facades\Log;
use LaravelCode\EventSourcing\Contracts\Locator\Locator;
use LaravelCode\EventSourcing\Events\AfterCommandWasHandled;
use LaravelCode\EventSourcing\Events\BeforeCommandIsHandled;
use LaravelCode\EventSourcing\Handler;
use LaravelCode\EventSourcing\Inflector\Inflector;

class CommandHandler implements Handler
{
    public function __construct(
        private Locator $locator,
        private Inflector $inflector,
        private bool $withEvents = true
    ) {
    }

    /**
     * @param string $eventName
     * @param array $events
     * @return void
     */
    public function handle(string $eventName, array $events): void
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

    private function emit(mixed $event): void
    {
        if ($this->withEvents) {
            event($event);
        }
    }
}
