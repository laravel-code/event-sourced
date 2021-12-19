<?php

namespace TestApp;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use LaravelCode\EventSouring\CommandBus;
use LaravelCode\EventSouring\Commands\CommandHandler;
use LaravelCode\EventSouring\Commands\StoreCommandHandler;
use LaravelCode\EventSouring\EventBus;
use LaravelCode\EventSouring\Inflector\ApplyInflector;
use LaravelCode\EventSouring\Inflector\HandleClassNameInflector;
use LaravelCode\EventSouring\Listener\EventListener;
use LaravelCode\EventSouring\Listener\StoreEventListener;
use LaravelCode\EventSouring\Locator\InMemoryInstanceOfLocator;
use TestApp\Commands\Handlers\PostHandler;
use TestApp\Commands\Posts\AbstractCommand;
use TestApp\Events\Listeners\PostListener;
use TestApp\Events\Posts\AbstractEvent;

class EventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();

        new CommandBus([
            new StoreCommandHandler(),
            new CommandHandler(
                new InMemoryInstanceOfLocator([
                    AbstractCommand::class => $this->app->make(PostHandler::class),
                ]),
                new HandleClassNameInflector()
            ),
        ]);

        new EventBus([
            new EventListener(
                new InMemoryInstanceOfLocator([
                    AbstractEvent::class => $this->app->make(PostListener::class),
                ]),
                new ApplyInflector()
            ),
            new StoreEventListener(),
        ]);
    }
}
