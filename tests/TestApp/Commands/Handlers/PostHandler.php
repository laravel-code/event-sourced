<?php

namespace TestApp\Commands\Handlers;

use TestApp\Commands\Posts\Change;
use TestApp\Commands\Posts\Create;
use TestApp\Commands\Posts\StopPropagation;
use TestApp\Models\Post;

class PostHandler
{
    public function handleCreate(Create $command): void
    {
        /** @var Post $post */
        $post = Post::create(
            $command->title,
            $command->body,
            $command->status,
            $command->getSecretKey(),
        );

        $post->publishEvents($command->getModel(), $command->getCommandId());
    }

    public function handleChange(Change $command): void
    {
        /** @var Post $post */
        $post = (new \TestApp\Models\Post)->find($command->id);

        $post->change(
            $command->title,
            $command->body,
            $command->status
        );

        $post->publishEvents($command->getModel(), $command->getCommandId());
    }

    /**
     * @throws \LaravelCode\EventSourcing\Error\StopPropagation
     */
    public function handleStopPropagation(StopPropagation $command): void
    {
        throw new \LaravelCode\EventSourcing\Error\StopPropagation();
    }
}
