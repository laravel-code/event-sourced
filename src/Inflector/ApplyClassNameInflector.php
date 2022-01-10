<?php

namespace LaravelCode\EventSourcing\Inflector;

class ApplyClassNameInflector implements Inflector
{
    public function execute(string $eventName): string
    {
        $reflection = new \ReflectionClass($eventName);

        return 'apply' . $reflection->getShortName();
    }
}
