<?php

namespace Integration\Console;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;
use Tests\AppTrait;

class ListenerTest extends TestCase
{
    use RefreshDatabase, AppTrait;

    public function setUp(): void
    {
        parent::setUp();

        File::deleteDirectory(app()->basePath() . '/app/Events');
    }

    public function tearDown(): void
    {
        parent::tearDown();

        File::deleteDirectory(app()->basePath() . '/app/Events');
    }

    public function testMake(): void
    {
        $command = $this->artisan('make:es:listener', ['name' => 'UserListener']);
        $command->execute();
        $command->assertSuccessful();

        $this->assertFileExists(app()->basePath() . '/app/Events/Listeners/UserListener.php');
    }
}
