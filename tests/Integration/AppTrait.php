<?php

namespace Tests;

use Illuminate\Routing\Router;
use LaravelCode\EventSourcing\ServiceProvider;
use TestApp\EventServiceProvider;
use TestApp\Http\Controllers\PostController;

trait AppTrait
{
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
            EventServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../fixtures');
    }

    /**
     * @param Router $router
     * @return void
     */
    protected function defineRoutes($router): void
    {
        $router->post('/create', [PostController::class, 'store']);
        $router->post('/create-partial', [PostController::class, 'storePartialEntity']);
        $router->post('/create-no-entity', [PostController::class, 'storeNoEntity']);
        $router->post('/no-handle', [PostController::class, 'noHandle']);
        $router->post('/stop-propagation', [PostController::class, 'stopPropagation']);
    }

    protected function defineEnvironment($app): void
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
