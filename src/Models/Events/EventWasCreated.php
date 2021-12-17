<?php

namespace LaravelCode\EventSouring\Models\Events;

use LaravelCode\EventSouring\Contracts\Event\Event;

class EventWasCreated
{
    /**
     * @var string
     */
    public string $id;
    /**
     * @var string
     */
    public string $type;
    /**
     * @var \LaravelCode\EventSouring\Contracts\Event\Event
     */
    public \LaravelCode\EventSouring\Contracts\Event\Event $payload;
    /**
     * @var string
     */
    public string $status;
    /**
     * @var string|null
     */
    public ?string $author;

    /**
     * @var string|null
     */
    public ?string $initiatorId;

    /**
     * @param string $id
     * @param string $type
     * @param \LaravelCode\EventSouring\Contracts\Event\Event $payload
     * @param string $status
     * @param string|null $author
     */
    public function __construct(string $id, string $type, Event $payload, string $status, ?string $author = null, ?string $initiatorId = null)
    {
        $this->id = $id;
        $this->type = $type;
        $this->payload = $payload;
        $this->status = $status;
        $this->author = $author;
        $this->initiatorId = $initiatorId;
    }
}
