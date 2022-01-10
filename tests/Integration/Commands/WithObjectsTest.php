<?php

namespace Tests\Commands;

use Carbon\Carbon;
use LaravelCode\EventSourcing\Payload;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use TestApp\Commands\Posts\WithObjects;

class WithObjectsTest extends TestCase
{
    /**
     * @dataProvider provider
     * @param array $data
     * @return void
     * @throws ReflectionException
     */
    public function testCommandFromPayload(array $data): void
    {
        /** @var WithObjects $command */
        $command = WithObjects::fromPayload(new Payload($data));
        $command->setAuthorId('user-0001');

        $this->assertInstanceOf(WithObjects::class, $command);

        $this->assertNotEmpty($command->commandId);
        $this->assertSame(Carbon::parse($data['date'])->toString(), $command->date->toString(), 'date did not return expected value');
    }

    /**
     * @dataProvider provider
     * @param array $data
     * @return void
     * @throws ReflectionException
     */
    public function testCommandToJson(array $data): void
    {
        /** @var WithObjects $command */
        $command = WithObjects::fromPayload(new Payload($data));
        $command->setAuthorId('user-0001');

        $payload = $command->jsonSerialize();

        $this->assertArrayHasKey('date', $payload, 'Field command_id expected in payload');
        $this->assertArrayHasKey('author_id', $payload, 'Field author_id expected in payload');

        $this->assertSame($data['date'], $payload['date'], 'title did not return expected value');
        $this->assertSame('user-0001', $payload['author_id'], 'author_id did not return expected value');
    }

    public function provider(): array
    {
        return [
            [[
                'date' => '2021-12-11T10:02:30+01:00',
            ]],
            [[
                'date' => '1998-12-11T01:02:30+01:00',
            ]],
        ];
    }
}
