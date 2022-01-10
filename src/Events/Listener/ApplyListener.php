<?php

namespace LaravelCode\EventSourcing\Events\Listener;

use Illuminate\Database\Eloquent\Model;
use LaravelCode\EventSourcing\Contracts\Event\Event;
use LaravelCode\EventSourcing\Error\EventVersionException;
use LaravelCode\EventSourcing\Inflector\ApplyClassNameInflector;

abstract class ApplyListener
{
    /**
     * @throws \Throwable
     */
    public function apply(Event $event): Model
    {   /** @var mixed $entity */
        $entity = $this->getEntity($event);
        if ($entity->version > $event->getVersion() && !$event->isBeingReplayed()) {
            throw new EventVersionException();
        }

        call_user_func([$entity,
            (new ApplyClassNameInflector())->execute(get_class($event)),
        ], $event);

        $entity->version = $event->getVersion();
        $entity->saveOrFail();

        return $entity;
    }

    abstract public function getEntity(Event $event): Model;
}
