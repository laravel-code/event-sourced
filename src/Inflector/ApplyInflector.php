<?php

namespace LaravelCode\EventSouring\Inflector;

class ApplyInflector implements Inflector
{
    public function execute($eventName): string
    {
        return 'apply';
    }
}
