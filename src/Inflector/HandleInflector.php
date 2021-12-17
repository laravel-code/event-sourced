<?php

namespace LaravelCode\EventSouring\Inflector;

class HandleInflector implements Inflector
{
    public function execute(string $eventName): string
    {
        return 'handle';
    }
}
