<?php

namespace LaravelCode\EventSouring\Inflector;

interface Inflector
{
    public function execute($eventName): string;
}
