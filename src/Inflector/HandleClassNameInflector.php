<?php

namespace LaravelCode\EventSouring\Inflector;

class HandleClassNameInflector implements Inflector
{
    public function execute($eventName): string
    {
        $reflection = new \ReflectionClass($eventName);

        return 'handle' . $reflection->getShortName();
    }
}
