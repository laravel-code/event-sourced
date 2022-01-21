<?php

namespace LaravelCode\EventSourcing\Commands;

use Illuminate\Support\Str;
use LaravelCode\EventSourcing\Helpers\FromPayloadTrait;
use LaravelCode\EventSourcing\Helpers\JsonSerializeTrait;
use LaravelCode\EventSourcing\Payload;

trait Storable
{
    use JsonSerializeTrait;
    use FromPayloadTrait {
        FromPayloadTrait::fromPayload as baseFromPayload;
    }

    public string|null $commandId = null;
    public string|int|null $authorId = null;
    public string $_model;

    /**
     * @param string $id
     */
    public function setCommandId(string $id): void
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

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->_model;
    }

    /**
     * @param string $model
     */
    public function setModel(string $model): void
    {
        $this->_model = $model;
    }

    /**
     * @throws \ReflectionException
     */
    public static function fromPayload(Payload $payload): self
    {
        $instance = self::baseFromPayload($payload);
        $instance->setCommandId(Str::uuid());

        return $instance;
    }

    public function handle(): void
    {
        event($this);
    }
}
