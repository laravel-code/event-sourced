<?php

namespace LaravelCode\EventSourcing\Commands;

use LaravelCode\EventSourcing\Commands\Handlers\CommandActionHandler;
use LaravelCode\EventSourcing\Contracts\Command\ShouldStore;
use LaravelCode\EventSourcing\EventBus;
use LaravelCode\EventSourcing\Events\AfterCommandWasHandled;
use LaravelCode\EventSourcing\Events\Command\CommandWasCreated;
use LaravelCode\EventSourcing\Events\Listeners\EventListener;
use LaravelCode\EventSourcing\Events\Listeners\StoreCommandDatabaseListener;
use LaravelCode\EventSourcing\Handler;
use LaravelCode\EventSourcing\Inflector\ApplyClassNameInflector;
use LaravelCode\EventSourcing\Inflector\HandleInflector;
use LaravelCode\EventSourcing\Locator\InMemoryInstanceOfLocator;
use LaravelCode\EventSourcing\Locator\InMemoryLocator;

class StoreCommandHandler extends CommandHandler implements Handler
{
    public function __construct()
    {
        $locator = new InMemoryInstanceOfLocator([
            ShouldStore::class => new CommandActionHandler(),
        ]);

        $inflector = new HandleInflector();

        parent::__construct($locator, $inflector, false);

        $storeCommandListener = new StoreCommandDatabaseListener();
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
