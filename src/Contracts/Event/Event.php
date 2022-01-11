<?php

namespace LaravelCode\EventSourcing\Contracts\Event;

use Carbon\Carbon;
use LaravelCode\EventSourcing\Payload;

/**
 * @property string $id
 */
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
     * @return int
     */
    public function getVersion(): int;

    /**
     * @param Carbon $date
     */
    public function setCreatedAt(Carbon $date): void;

    /**
     * @param Carbon $date
     */
    public function setUpdatedAt(Carbon $date): void;

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

    /**
     * @param int|string $id
     * @return void
     */
    public function setId(int|string $id): void;
}
