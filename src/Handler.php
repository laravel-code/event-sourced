<?php

namespace LaravelCode\EventSouring;

interface Handler
{
    public function handle($eventName, $events);
}
