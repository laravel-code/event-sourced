<?php

namespace LaravelCode\EventSouring\Models\Events;

use LaravelCode\EventSouring\Contracts\Command\ShouldStore;
use LaravelCode\EventSouring\Contracts\Event\Event;
use LaravelCode\EventSouring\Events\Storable;

class CommandWasCreated implements Event
{
    use Storable;

    /**
     * @var string|int
     */
    public string|int $id;
    /**
     * @var string
     */
    public string $type;
    /**
     * @var ShouldStore
     */
    public ShouldStore $payload;
    /**
     * @var string
     */
    public string $status;
    /**
     * @var string|null
     */
    public ?string $author;

    /**
     * @param string $id
     * @param string $type
     * @param ShouldStore $payload
     * @param string $status
     * @param string|null $author
     */
    public function __construct(string $id, string $type, ShouldStore $payload, string $status, ?string $author)
    {
        $this->id = $id;
        $this->type = $type;
        $this->payload = $payload;
        $this->status = $status;
        $this->author = $author;
    }
}
