<?php

namespace Integration\Console;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;
use Tests\AppTrait;

class HandlerTest extends TestCase
{
    use RefreshDatabase, AppTrait;

    public function setUp(): void
    {
        parent::setUp();

        File::deleteDirectory(app()->basePath() . '/app/Commands');
    }

    public function tearDown(): void
    {
        parent::tearDown();

        File::deleteDirectory(app()->basePath() . '/app/Commands');
    }

    public function testMake(): void
    {
        $command = $this->artisan('make:es:handler', ['name' => 'UserHandler']);
        $command->execute();
        $command->assertSuccessful();

        $this->assertFileExists(app()->basePath() . '/app/Commands/Handlers/UserHandler.php');
    }
}
