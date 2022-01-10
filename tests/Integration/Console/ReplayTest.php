<?php

namespace Integration\Console;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use LaravelCode\EventSourcing\Models\Command;
use LaravelCode\EventSourcing\Models\Event;
use Orchestra\Testbench\TestCase;
use TestApp\Commands\Posts\Change;
use TestApp\Commands\Posts\Create;
use TestApp\Models\Post;
use Tests\AppTrait;

class ReplayTest extends TestCase
{
    use RefreshDatabase, AppTrait;

    public function testReplay(): void
    {
        $this->makeEvents();
        $command = $this->artisan('event:replay');
        $command->execute();
        $command->assertSuccessful();

        $this->assertEquals(1, Post::count(), 'Unexpected count for table posts]');
    }

    private function makeEvents(): void
    {
        Post::truncate();
        Event::truncate();
        Command::truncate();

        $this->assertEquals(0, Post::count(), 'Unexpected count for table posts');
        $this->assertEquals(0, Event::count(), 'Unexpected count for table events');

        $command = new Create('Title', 'body string', 'new', 'some secret key');
        $command->setCommandId(Str::uuid());
        dispatch($command);

        $this->assertEquals(1, Post::count(), 'Unexpected count for table posts]');
        $id = Post::first()->id;

        $command = new Change($id, 'Updated title', 'Updated body', 'active');
        $command->setCommandId(Str::uuid());
        dispatch($command);

        $this->assertEquals(4, Event::count(), 'Unexpected count for table events');

        $this->assertEquals(1, Post::count(), 'Unexpected count for table posts');
        Post::truncate();
        $this->assertEquals(0, Post::count(), 'Unexpected count for table posts');
    }
}
