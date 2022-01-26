<?php

namespace LaravelCode\EventSourcing\Events\Listeners;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
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

        if (isset($entity->id) && !isset($event->id)) {
            Log::debug(sprintf('Entity id %s set for event %s', $entity->id, get_class($event)));

            $event->setId($entity->id);
        }

        return $entity;
    }

    abstract public function getEntity(Event $event): Model;
}
