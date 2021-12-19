<?php

namespace TestApp\Commands\Handlers;

use TestApp\Commands\Posts\Change;
use TestApp\Commands\Posts\Create;
use TestApp\Commands\Posts\StopPropagation;
use TestApp\Models\Post;

class PostHandler
{
    public function handleCreate(Create $command)
    {
        /** @var Post $post */
        $post = Post::make(
            $command->title,
            $command->body,
            $command->status,
            $command->getSecretKey(),
        );

        $post->publishEvents($command->getCommandId());
    }

    public function handleChange(Change $command)
    {
        /** @var Post $post */
        $post = (new \TestApp\Models\Post)->find($command->getId());

        $post->change(
            $command->title,
            $command->body,
            $command->status
        );

        $post->publishEvents($command->getCommandId());
    }

    /**
     * @throws \LaravelCode\EventSouring\Error\StopPropagation
     */
    public function handleStopPropagation(StopPropagation $command)
    {
        throw new \LaravelCode\EventSouring\Error\StopPropagation();
    }
}
