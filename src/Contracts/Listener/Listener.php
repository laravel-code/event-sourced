<?php

namespace LaravelCode\EventSouring\Contracts\Listener;

interface Listener
{
    public function handle($eventName, $events);
}
