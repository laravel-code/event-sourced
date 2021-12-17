<?php

namespace LaravelCode\EventSouring\Contracts\Command;

use LaravelCode\EventSouring\Payload;

interface Command
{
    public function setCommandId(string $id): void;

    /**
     * @return string|null
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
}
