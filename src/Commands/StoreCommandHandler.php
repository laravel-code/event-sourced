<?php

namespace LaravelCode\EventSouring\Commands;

use LaravelCode\EventSouring\Commands\Handlers\CommandActionHandler;
use LaravelCode\EventSouring\Contracts\Command\ShouldStore;
use LaravelCode\EventSouring\EventBus;
use LaravelCode\EventSouring\Handler;
use LaravelCode\EventSouring\Inflector\ApplyInflector;
use LaravelCode\EventSouring\Inflector\HandleInflector;
use LaravelCode\EventSouring\Listener\EventListener;
use LaravelCode\EventSouring\Locator\InMemoryInstanceOfLocator;
use LaravelCode\EventSouring\Locator\InMemoryLocator;
use LaravelCode\EventSouring\Models\Events\CommandWasCreated;
use LaravelCode\EventSouring\Models\Listeners\StoreCommandListener;

class StoreCommandHandler extends CommandHandler implements Handler
{
    public function __construct()
    {
        $locator = new InMemoryInstanceOfLocator([
            ShouldStore::class => new CommandActionHandler(),
        ]);

        $inflector = new HandleInflector();

        parent::__construct($locator, $inflector, false);

        new EventBus([
            new EventListener(
                new InMemoryLocator([
                    CommandWasCreated::class => new StoreCommandListener(),
                ]),
                new ApplyInflector()
            ),
        ]);
    }
}
