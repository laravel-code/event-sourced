<?php

namespace TestApp;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use LaravelCode\EventSourcing\CommandBus;
use LaravelCode\EventSourcing\Commands\CommandHandler;
use LaravelCode\EventSourcing\Commands\StoreCommandHandler;
use LaravelCode\EventSourcing\EventBus;
use LaravelCode\EventSourcing\Inflector\ApplyInflector;
use LaravelCode\EventSourcing\Inflector\HandleClassNameInflector;
use LaravelCode\EventSourcing\Listener\CommandStatusListener;
use LaravelCode\EventSourcing\Listener\EventListener;
use LaravelCode\EventSourcing\Listener\StoreEventListener;
use LaravelCode\EventSourcing\Locator\InMemoryInstanceOfLocator;
use LaravelCode\EventSourcing\Models\Command;
use TestApp\Commands\Handlers\PostHandler;
use TestApp\Commands\Posts\AbstractCommand;
use TestApp\Events\Listeners\PostListener;
use TestApp\Events\Posts\AbstractEvent;
use TestApp\Events\Posts\BodyWasChanged;
use TestApp\Events\Posts\StatusWasChanged;
use TestApp\Events\Posts\TitleWasChanged;
use TestApp\Events\Posts\WasCreated;

class EventServiceProvider extends ServiceProvider
{
    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
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
            new CommandStatusListener([
                WasCreated::class => Command::STATUS_SUCCEEDED,
                BodyWasChanged::class => Command::STATUS_SUCCEEDED,
                StatusWasChanged::class => Command::STATUS_SUCCEEDED,
                TitleWasChanged::class => Command::STATUS_SUCCEEDED,
            ]),
        ]);
    }
}
