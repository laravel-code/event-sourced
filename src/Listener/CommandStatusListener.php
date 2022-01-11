<?php

namespace LaravelCode\EventSourcing\Listener;

use Illuminate\Support\Facades\Log;
use LaravelCode\EventSourcing\Contracts\Event\Event;
use LaravelCode\EventSourcing\Handler;
use LaravelCode\EventSourcing\Inflector\ApplyClassNameInflector;
use LaravelCode\EventSourcing\Locator\InMemoryLocator;
use LaravelCode\EventSourcing\Models\Events\CommandStatusWasChanged;
use LaravelCode\EventSourcing\Models\Listeners\StoreCommandListener;

class CommandStatusListener extends EventListener implements Handler
{
    const SUCCESS = 'success';
    const FAILED = 'failed';

    public function __construct(array $commands = [])
    {
        $locator = new InMemoryLocator($commands);

        parent::__construct($locator, new ApplyClassNameInflector());
    }

    public function handle(string $eventName, array $events): void
    {
        $status = $this->locator->execute($eventName, $events);

        if (!$status) {
            return;
        }

        $handler = new StoreCommandListener();
        $name = $this->inflector->execute(CommandStatusWasChanged::class);

        if (!is_callable([$handler, $name])) {
            Log::error(sprintf('Could not call %s on listener %s', $name, get_class($handler)));

            return;
        }

        /** @var Event $event */
        foreach ($events as $event) {
            if (!$event instanceof Event) {
                continue;
            }

            $this->processEvent(
                new CommandStatusWasChanged($event->getCommandId(), $status),
                $handler,
                $name
            );
        }
    }

    private function processEvent(mixed $event, mixed $handler, string $name): void
    {
        call_user_func([$handler, $name], $event);
    }
}
