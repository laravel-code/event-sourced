<?php

namespace Tests\Events;

use LaravelCode\EventSouring\Payload;
use TestApp\Events\Posts\WasCreated;

class EventTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider provider
     * @param array $data
     * @return void
     * @throws \ReflectionException
     */
    public function testEventFromPayload(array $data): void
    {
        /** @var WasCreated $event */
        $event = WasCreated::fromPayload(new Payload($data));
        $this->assertInstanceOf(WasCreated::class, $event);

        $this->assertSame($data['id'], $event->id, 'id did not return expected value');
        $this->assertSame($data['title'], $event->title, 'title did not return expected value');
        $this->assertSame($data['body'], $event->body, 'body did not return expected value');
        $this->assertSame($data['status'], $event->status, 'status did not return expected value');
        $this->assertSame($data['secret_key'], $event->getSecretKey(), 'secretKey did not return expected value');
    }

    /**
     * @dataProvider provider
     * @param array $data
     * @return void
     * @throws \ReflectionException
     */
    public function testEventToJson(array $data): void
    {
        /** @var WasCreated $event */
        $event = WasCreated::fromPayload(new Payload($data));
        $payload = $event->jsonSerialize();

        $this->assertArrayHasKey('id', $payload, 'Field id expected in payload');
        $this->assertArrayHasKey('title', $payload, 'Field title expected in payload');
        $this->assertArrayHasKey('body', $payload, 'Field body expected in payload');
        $this->assertArrayHasKey('status', $payload, 'Field status expected in payload');
        $this->assertArrayNotHasKey('secret_key', $payload, 'Field secretKey expected in payload');

        $this->assertSame($data['id'], $payload['id'], 'id did not return expected value');
        $this->assertSame($data['title'], $payload['title'], 'title did not return expected value');
        $this->assertSame($data['body'], $payload['body'], 'body did not return expected value');
        $this->assertSame($data['status'], $payload['status'], 'status did not return expected value');
    }

    /**
     * @dataProvider provider
     * @param array $data
     * @return void
     * @throws \ReflectionException
     */
    public function testEventReplayed(array $data): void
    {
        /** @var WasCreated $event */
        $event = WasCreated::fromPayload(new Payload($data));
        $event->setBeingReplayed(true);
        $payload = $event->jsonSerialize();

        $this->assertArrayHasKey('being_replayed', $payload);
        $this->assertTrue($payload['being_replayed']);
    }

    public function provider(): array
    {
        return [
            [[
                'id' => '40d1a4b3-4c76-4def-8f9c-0262eaef7c57',
                'title' => 'Some title',
                'body' => 'Some Content',
                'status' => 'active',
                'secret_key' => 'secret key',
            ]],
            [[
                'id' => '5ace66d2-ba8f-45de-a9dd-0824f165ff08',
                'title' => 'Other title',
                'body' => 'Other Content',
                'status' => 'draft',
                'secret_key' => 'secret key',
            ]],
        ];
    }
}
