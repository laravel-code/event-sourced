<?php

namespace LaravelCode\EventSouring\Events;

use LaravelCode\EventSouring\Helpers\FromPayloadTrait;
use LaravelCode\EventSouring\Helpers\JsonSerializeTrait;

/**
 * @property string|null $id;
 */
trait Storable
{
    use JsonSerializeTrait, FromPayloadTrait;

    public int $_version = 0;
    public string|null $eventId = null;
    public string|null $commandId = null;
    public string|int|null $authorId = null;
    public bool $beingReplayed = false;

    public function getId(): string|int
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setEventId(string $id): void
    {
        $this->eventId = $id;
    }

    /**
     * @return string|null
     */
    public function getEventId(): string|null
    {
        return $this->eventId;
    }

    /**
     * @param int $version
     */
    public function setVersion(int $version): void
    {
        $this->_version = $version;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->_version;
    }

    /**
     * @param string|null $id
     */
    public function setCommandId(string|null $id): void
    {
        $this->commandId = $id;
    }

    /**
     * @return string|null
     */
    public function getCommandId(): string|null
    {
        return $this->commandId;
    }

    /**
     * @return int|string|null
     */
    public function getAuthorId(): int|string|null
    {
        return $this->authorId;
    }

    /**
     * @param int|string|null $authorId
     */
    public function setAuthorId(int|string|null $authorId): void
    {
        $this->authorId = $authorId;
    }

    public function isBeingReplayed(): bool
    {
        return $this->beingReplayed;
    }

    public function setBeingReplayed(bool $value): void
    {
        $this->beingReplayed = $value;
    }
}
