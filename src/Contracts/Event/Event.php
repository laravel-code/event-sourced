<?php

namespace LaravelCode\EventSouring\Contracts\Event;

use LaravelCode\EventSouring\Payload;

interface Event
{
    /**
     * @param string $id
     */
    public function setEventId(string $id): void;

    /**
     * @param int $version
     */
    public function setVersion(int $version): void;

    /**
     * @return string|null
     */
    public function getEventId(): string|null;

    /**
     * @param string $id
     */
    public function setCommandId(string|null $id): void;

    /**
     * @return string
     */
    public function getCommandId(): string|null;

    /**
     * @return int|string|null
     */
    public function getAuthorId(): int|string|null;

    /**
     * @param int|string|null $authorId
     */
    public function setAuthorId(int|string|null $authorId): void;

    /**
     * @return array
     */
    public function jsonSerialize(): array;

    /**
     * @throws \ReflectionException
     */
    public static function fromPayload(Payload $payload): object;

    /**
     * @return bool
     */
    public function isBeingReplayed(): bool;

    /**
     * @param bool $value
     * @return void
     */
    public function setBeingReplayed(bool $value): void;
}
