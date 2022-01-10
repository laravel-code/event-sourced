<?php

namespace LaravelCode\EventSourcing\Inflector;

interface Inflector
{
    public function execute(string $eventName): string;
}
