<?php

namespace LaravelCode\EventSourcing\Commands\Handlers;

use LaravelCode\EventSourcing\Contracts\Command\ShouldStore;
use LaravelCode\EventSourcing\Models\Command;

class CommandActionHandler
{
    public function handle(ShouldStore $command): void
    {
        $entity = Command::instance(
            $command->getCommandId(),
            get_class($command),
            $command,
            Command::STATUS_RECEIVED,
            $command->getAuthorId()
        );

        $entity->publishEvents(
            $command->getCommandId(),
            $command->getAuthorId()
        );
    }
}
