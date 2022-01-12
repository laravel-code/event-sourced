<?php

namespace LaravelCode\EventSourcing;

use LaravelCode\EventSourcing\Console\Commands\EsCommand;
use LaravelCode\EventSourcing\Console\Commands\ESEvent;
use LaravelCode\EventSourcing\Console\Commands\EsHandler;
use LaravelCode\EventSourcing\Console\Commands\EsListener;
use LaravelCode\EventSourcing\Console\Commands\EventReplay;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(realpath(__DIR__ . '/migrations'));

            $this->commands([
                EsCommand::class,
                ESEvent::class,
                EsHandler::class,
                EsListener::class,
                EventReplay::class,
            ]);
        }
    }
}
