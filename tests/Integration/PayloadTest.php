<?php

namespace Integration;

use LaravelCode\EventSourcing\Error\PayloadException;
use LaravelCode\EventSourcing\Payload;

class PayloadTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @return void
     */
    public function testGetters(array $data): void
    {
        $payload = new Payload($data);

        $this->assertSame($data['name'], $payload->get('name'));
        $this->assertSame($data['birthday'], $payload->get('birthday'));
    }

    /**
     * @dataProvider dataProvider
     *
     * @return void
     */
    public function testSetters(array $data): void
    {
        $payload = new Payload($data);

        $payload->set('city', 'Somewhere ATL');

        $this->assertSame($data['name'], $payload->get('name'));
        $this->assertSame($data['birthday'], $payload->get('birthday'));
        $this->assertSame('Somewhere ATL', $payload->get('city'));
    }

    /**
     * @dataProvider dataProvider
     *
     * @return void
     */
    public function testSetterExistingVar(array $data): void
    {
        $this->expectException(PayloadException::class);

        $payload = new Payload($data);
        $payload->set('name', 'Johnson Doe');
    }

    public function dataProvider(): array
    {
        return [
            [['name' => 'Jane Doe', 'birthday' => '2010-09-24']],
            [['name' => 'John Doe', 'birthday' => '2012-17-07']],
            [['name' => 'Edgar Doe', 'birthday' => '2014-11-14']],
        ];
    }
}
