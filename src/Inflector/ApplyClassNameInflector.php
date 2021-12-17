<?php

namespace LaravelCode\EventSouring\Inflector;

class ApplyClassNameInflector implements Inflector
{
    public function execute($eventName): string
    {
        $reflection = new \ReflectionClass($eventName);

        return 'apply' . $reflection->getShortName();
    }
}
