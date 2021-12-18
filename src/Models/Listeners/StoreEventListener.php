<?php

namespace LaravelCode\EventSouring\Models\Listeners;

use LaravelCode\EventSouring\Contracts\Event\ShouldStore;
use LaravelCode\EventSouring\Models\Event;

class StoreEventListener
{
    public function apply(ShouldStore $event): void
    {
        $entity = new Event();
        $entity->id = $event->getEventId();
        $entity->command_id = $event->getCommandId();
        $entity->author_id = $event->getAuthorId();
        $entity->entity_id = $event->id;
        $entity->version = $event->getVersion();
        $entity->payload = $event;
        $entity->type = get_class($event);

        $entity->save();
    }
}
