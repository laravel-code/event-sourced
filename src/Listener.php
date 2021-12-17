<?php

namespace LaravelCode\EventSouring;

use Illuminate\Database\Eloquent\Model;
use LaravelCode\EventSouring\Contracts\Event\Event;
use LaravelCode\EventSouring\Error\MethodNotImplemented;

class Listener
{
    /**
     * @throws MethodNotImplemented
     * @throws \ReflectionException
     */
    public function execute(Model $model, Event $event): void
    {
        $reflection = new \ReflectionClass($event);
        $method = 'apply' . $reflection->getShortName();

        if (!is_callable([$model, $method])) {
            throw new MethodNotImplemented();
        }

        call_user_func([$model, $method], $event);
    }
}
