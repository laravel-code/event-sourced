<?php

namespace TestApp\Events\Listeners;

use Illuminate\Database\Eloquent\Model;
use LaravelCode\EventSouring\Error\EventVersionException;
use LaravelCode\EventSouring\Inflector\ApplyClassNameInflector;
use TestApp\Events\Posts\AbstractEvent;
use TestApp\Events\Posts\WasCreated;
use TestApp\Models\Post;

class PostListener
{
    /**
     * @throws \Throwable
     */
    public function apply(AbstractEvent $event): Model
    {
        $entity = $this->getEntity($event);
        if ($entity->version > $event->getVersion()) {
            throw new EventVersionException();
        }

        call_user_func([$entity,
            (new ApplyClassNameInflector())->execute($event),
        ], $event);

        $entity->version = $event->getVersion();
        $entity->saveOrFail();

        return $entity;
    }

    private function getEntity(AbstractEvent $event): Post
    {
        if (get_class($event) == WasCreated::class) {
            $post = new Post();
            $post->id = $event->getId();

            return $post;
        }

        return (new \TestApp\Models\Post)->find($event->getId());
    }
}
