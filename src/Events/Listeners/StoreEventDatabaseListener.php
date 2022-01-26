<?php

namespace LaravelCode\EventSourcing\Events\Listeners;

use LaravelCode\EventSourcing\Contracts\Event\ShouldStore;
use LaravelCode\EventSourcing\Models\Event;

class StoreEventDatabaseListener
{
    public function apply(ShouldStore $event): void
    {
        $entity = new Event();
        $entity->id = $event->getEventId();
        $entity->model = $event->getModel();
        $entity->command_id = $event->getCommandId();
        $entity->author_id = $event->getAuthorId();
        $entity->entity_id = $event->id;
        $entity->version = $event->getVersion();
        $entity->payload = $event;
        $entity->type = get_class($event);

        $entity->save();
    }
}
