<?php

namespace LaravelCode\EventSourcing\Commands;

use LaravelCode\EventSourcing\Commands\Handlers\CommandActionHandler;
use LaravelCode\EventSourcing\Contracts\Command\ShouldStore;
use LaravelCode\EventSourcing\EventBus;
use LaravelCode\EventSourcing\Events\AfterCommandWasHandled;
use LaravelCode\EventSourcing\Handler;
use LaravelCode\EventSourcing\Inflector\ApplyClassNameInflector;
use LaravelCode\EventSourcing\Inflector\HandleInflector;
use LaravelCode\EventSourcing\Listener\EventListener;
use LaravelCode\EventSourcing\Locator\InMemoryInstanceOfLocator;
use LaravelCode\EventSourcing\Locator\InMemoryLocator;
use LaravelCode\EventSourcing\Models\Events\CommandWasCreated;
use LaravelCode\EventSourcing\Models\Listeners\StoreCommandListener;

class StoreCommandHandler extends CommandHandler implements Handler
{
    public function __construct()
    {
        $locator = new InMemoryInstanceOfLocator([
            ShouldStore::class => new CommandActionHandler(),
        ]);

        $inflector = new HandleInflector();

        parent::__construct($locator, $inflector, false);

        $storeCommandListener = new StoreCommandListener();
        new EventBus([
            new EventListener(
                new InMemoryLocator([
                    CommandWasCreated::class => $storeCommandListener,
                    AfterCommandWasHandled::class => $storeCommandListener,
                ]),
                new ApplyClassNameInflector()
            ),
        ]);
    }
}
