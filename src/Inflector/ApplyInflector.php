<?php

namespace LaravelCode\EventSouring\Inflector;

class ApplyInflector implements Inflector
{
    public function execute(string $eventName): string
    {
        return 'apply';
    }
}
