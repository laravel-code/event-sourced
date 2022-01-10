<?php

namespace LaravelCode\EventSourcing\Inflector;

class ApplyInflector implements Inflector
{
    public function execute(string $eventName): string
    {
        return 'apply';
    }
}
