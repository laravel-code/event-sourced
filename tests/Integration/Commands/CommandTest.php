<?php

namespace Tests\Commands;

use LaravelCode\EventSouring\Payload;
use TestApp\Commands\Posts\Create;

class CommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider provider
     * @return void
     * @throws \ReflectionException
     */
    public function testCommandFromPayload($data)
    {
        /** @var Create $command */
        $command = Create::fromPayload(new Payload($data));
        $command->setAuthorId('user-0001');

        $this->assertInstanceOf(Create::class, $command);

        $this->assertNotEmpty($command->commandId);
        $this->assertSame($data['title'], $command->title, 'title did not return expected value');
        $this->assertSame($data['body'], $command->body, 'body did not return expected value');
        $this->assertSame($data['status'], $command->status, 'status did not return expected value');
        $this->assertSame($data['secret_key'], $command->getSecretKey(), 'secret_key did not return expected value');
        $this->assertSame('user-0001', $command->getAuthorId(), 'authorId did not return expected value');
    }

    /**
     * @dataProvider provider
     * @param $data
     * @return void
     */
    public function testCommandToJson($data)
    {
        /** @var Create $command */
        $command = Create::fromPayload(new Payload($data));
        $command->setAuthorId('user-0001');

        $payload = $command->jsonSerialize();

        $this->assertArrayHasKey('command_id', $payload, 'Field command_id expected in payload');
        $this->assertArrayHasKey('title', $payload, 'Field title expected in payload');
        $this->assertArrayHasKey('body', $payload, 'Field body expected in payload');
        $this->assertArrayHasKey('status', $payload, 'Field status expected in payload');
        $this->assertArrayNotHasKey('secret_key', $payload, 'Field secret_key expected in payload');
        $this->assertArrayHasKey('author_id', $payload, 'Field author_id expected in payload');

        $this->assertSame($data['title'], $payload['title'], 'title did not return expected value');
        $this->assertSame($data['body'], $payload['body'], 'body did not return expected value');
        $this->assertSame($data['status'], $payload['status'], 'status did not return expected value');
        $this->assertSame('user-0001', $payload['author_id'], 'author_id did not return expected value');
    }

    public function provider()
    {
        return [
            [[
                'title' => 'Some title',
                'body' => 'Some Content',
                'status' => 'active',
                'secret_key' => 'secret key',
            ]],
            [[
                'title' => 'Other title',
                'body' => 'Other Content',
                'status' => 'draft',
                'secret_key' => 'secret key',
            ]],
        ];
    }
}
