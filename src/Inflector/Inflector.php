<?php

namespace LaravelCode\EventSouring\Inflector;

interface Inflector
{
    public function execute(string $eventName): string;
}
