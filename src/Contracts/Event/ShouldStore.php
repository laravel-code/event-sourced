<?php

namespace LaravelCode\EventSourcing\Contracts\Event;

/**
 * @property mixed $id
 * @method int getVersion()
 */
interface ShouldStore extends Event
{
}
