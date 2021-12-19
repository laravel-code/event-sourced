<?php

namespace Tests\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Router;
use LaravelCode\EventSouring\Models\Command;
use LaravelCode\EventSouring\Models\Event;
use LaravelCode\EventSouring\ServiceProvider;
use Orchestra\Testbench\TestCase;
use TestApp\EventServiceProvider;
use TestApp\Http\Controllers\PostController;
use TestApp\Models\Post;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testStoreCommandAndEvent(): void
    {
        $this->assertEquals(0, Command::count(), 'Unexpected count for table commands');
        $this->assertEquals(2, Post::count(), 'Unexpected count for table posts');
        $this->assertEquals(0, Event::count(), 'Unexpected count for table events');

        $response = $this->post('/create', [
            'title' => 'TITLE',
            'body' => 'BODY',
            'status' => 'STATUS',
            'secret_key' => 'SECRET_KEY',
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'success', 'command_id', 'entity' => ['id', 'title'],
        ]);
        $response->assertJsonCount(3);

        $this->assertEquals(1, Command::count(), 'Unexpected count for table commands');
        $this->assertEquals(3, Post::count(), 'Unexpected count for table posts');
        $this->assertEquals(1, Event::count(), 'Unexpected count for table events');
    }

    public function testNoEntityReturned(): void
    {
        $response = $this->post('/create-no-entity', [
            'title' => 'TITLE',
            'body' => 'BODY',
            'status' => 'STATUS',
            'secret_key' => 'SECRET_KEY',
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'success', 'command_id',
        ]);
        $response->assertJsonCount(2);
    }

    public function testPartialEntityReturned(): void
    {
        $response = $this->post('/create-partial', [
            'title' => 'TITLE',
            'body' => 'BODY',
            'status' => 'STATUS',
            'secret_key' => 'SECRET_KEY',
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'success', 'command_id', 'entity' => ['id', 'title'],
        ]);

        $response->assertJsonFragment([
            'success' => true,
            'title' => 'TITLE',
        ]);
    }

    public function testNoHandle(): void
    {
        $response = $this->post('/no-handle', [], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(500);
        $response->assertExactJson(['message' => 'Server Error']);
    }

    public function testStopPropagation(): void
    {
        $response = $this->post('/stop-propagation', [], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200);
    }

    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
            EventServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../fixtures');
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
