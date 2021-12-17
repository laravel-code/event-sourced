<?php

namespace LaravelCode\EventSouring\Inflector;

class HandleInflector implements Inflector
{
    public function execute($eventName): string
    {
        return 'handle';
    }
}
