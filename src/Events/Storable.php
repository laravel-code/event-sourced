<?php

namespace LaravelCode\EventSourcing\Events;

use Carbon\Carbon;
use LaravelCode\EventSourcing\Helpers\FromPayloadTrait;
use LaravelCode\EventSourcing\Helpers\JsonSerializeTrait;

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
    private Carbon|null $createdAt = null;
    private Carbon|null $updatedAt = null;

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

    /**
     * @param Carbon $date
     */
    public function setCreatedAt(Carbon $date): void
    {
        $this->createdAt = $date;
    }

    /**
     * @param Carbon $date
     */
    public function setUpdatedAt(Carbon $date): void
    {
        $this->updatedAt = $date;
    }
}
