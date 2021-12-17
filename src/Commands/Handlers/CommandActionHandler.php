<?php

namespace LaravelCode\EventSouring\Commands\Handlers;

use LaravelCode\EventSouring\Contracts\Command\ShouldStore;
use LaravelCode\EventSouring\Models\Command;

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
