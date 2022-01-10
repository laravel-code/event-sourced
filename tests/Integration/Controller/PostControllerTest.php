<?php

namespace Tests\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelCode\EventSourcing\Models\Command;
use LaravelCode\EventSourcing\Models\Event;
use Orchestra\Testbench\TestCase;
use TestApp\Models\Post;
use Tests\AppTrait;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;
    use AppTrait;

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
}
