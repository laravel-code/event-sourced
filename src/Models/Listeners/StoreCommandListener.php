<?php

namespace LaravelCode\EventSouring\Models\Listeners;

use LaravelCode\EventSouring\Models\Command;
use LaravelCode\EventSouring\Models\Events\CommandWasCreated;

class StoreCommandListener extends \LaravelCode\EventSouring\Listener
{
    public function apply(CommandWasCreated $event): void
    {
        $command = new Command();
        $command->applyCommandWasCreated($event);
        $command->saveOrFail();
    }
}
