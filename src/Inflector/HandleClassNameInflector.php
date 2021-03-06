<?php

namespace LaravelCode\EventSourcing\Inflector;

class HandleClassNameInflector implements Inflector
{
    public function execute(string $eventName): string
    {
        $reflection = new \ReflectionClass($eventName);

        return 'handle' . $reflection->getShortName();
    }
}
