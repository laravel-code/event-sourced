<?php

namespace LaravelCode\EventSourcing\Inflector;

class HandleInflector implements Inflector
{
    public function execute(string $eventName): string
    {
        return 'handle';
    }
}
