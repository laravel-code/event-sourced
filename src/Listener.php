<?php

namespace LaravelCode\EventSouring;

use Illuminate\Database\Eloquent\Model;
use LaravelCode\EventSouring\Error\MethodNotImplemented;
use Webmozart\Assert\Assert;

class Listener
{
    /**
     * @throws MethodNotImplemented
     * @throws \ReflectionException
     */
    public function execute(Model $model, $event)
    {
        Assert::isCallable($event, sprintf('%s in not callable.', $event));

        $reflection = new \ReflectionClass($event);
        $method = 'apply' . $reflection->getShortName();

        if (!is_callable([$model, $method])) {
            throw new MethodNotImplemented();
        }

        call_user_func([$model, $method], $event);
    }
}
