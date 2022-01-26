<?php

namespace TestApp\Events\Listeners;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use LaravelCode\EventSourcing\Contracts\Event\Event;
use LaravelCode\EventSourcing\Events\Listeners\ApplyListener;
use TestApp\Models\Post;

class PostListener extends ApplyListener
{
    public function getEntity(Event $event): Model
    {
        try {
            return (new \TestApp\Models\Post)->findOrFail($event->id);
        } catch (ModelNotFoundException) {
            $entity = new Post();
            $entity->id = $event->id;

            return $entity;
        }
    }
}
