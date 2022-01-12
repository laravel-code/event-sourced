<?php

namespace Integration\Console;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;
use Tests\AppTrait;

class EventTest extends TestCase
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
        $command = $this->artisan('make:es:event', ['name' => 'User/WasCreated']);
        $command->execute();
        $command->assertSuccessful();

        $this->assertFileExists(app()->basePath() . '/app/Events/User/WasCreated.php');
    }
}
