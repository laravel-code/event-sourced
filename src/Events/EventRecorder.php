<?php

namespace LaravelCode\EventSourcing\Events;

use function event;
use Illuminate\Support\Str;
use LaravelCode\EventSourcing\Contracts\Event\Event;
use LaravelCode\EventSourcing\Error\MethodNotImplemented;
use LaravelCode\EventSourcing\Inflector\ApplyClassNameInflector;

/**
 * @property int $version
 */
trait EventRecorder
{
    private array $_eventStack = [];

    /**
     * @throws MethodNotImplemented
     * @throws \ReflectionException
     */
    public function record(Event $event): void
    {
        $inflector = new ApplyClassNameInflector();
        $method = $inflector->execute(get_class($event));

        if (!is_callable([$this, $method])) {
            throw new MethodNotImplemented();
        }

        $event->setVersion($this->version ? $this->version + 1 : 1);

        call_user_func([$this, $method], $event);

        $this->_eventStack[] = $event;
    }

    public function getUnpublishedEvents(): array
    {
        return $this->_eventStack;
    }

    public function publishEvents(?string $commandId = null, ?string $authorId = null): void
    {
        foreach ($this->getUnpublishedEvents() as $event) {
            if ($event instanceof Event) {
                $event->setEventId(Str::uuid());
                $event->setCommandId($commandId ?? null);
                $event->setAuthorId($authorId ?? null);
            }
            event($event);
        }
    }
}
