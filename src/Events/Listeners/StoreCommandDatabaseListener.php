<?php

namespace LaravelCode\EventSourcing\Events\Listeners;

use LaravelCode\EventSourcing\Events\AfterCommandWasHandled;
use LaravelCode\EventSourcing\Events\Command\CommandStatusWasChanged;
use LaravelCode\EventSourcing\Events\Command\CommandWasCreated;
use LaravelCode\EventSourcing\Models\Command;

class StoreCommandDatabaseListener
{
    public function applyCommandWasCreated(CommandWasCreated $event): void
    {
        $command = new Command();
        $command->applyCommandWasCreated($event);
        $command->saveOrFail();
    }

    public function applyAfterCommandWasHandled(AfterCommandWasHandled $event): void
    {
        $command = $event->command;

        /** @var Command $entity */
        $entity = Command::find($command->getCommandId());

        if ($entity->status === Command::STATUS_RECEIVED) {
            $entity->status = Command::STATUS_HANDLED;
            $entity->saveOrFail();
        }
    }

    public function applyCommandStatusWasChanged(CommandStatusWasChanged $event): void
    {
        if (!$event->id) {
            return;
        }

        /** @var Command $entity */
        $entity = Command::find($event->id);

        if ($entity->status !== $event->status) {
            $entity->status = $event->status;
        }

        $entity->save();
    }
}
