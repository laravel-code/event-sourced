<?php

namespace LaravelCode\EventSouring\Helpers;

use Illuminate\Support\Str;
use LaravelCode\EventSouring\Payload;
use ReflectionParameter;

trait FromPayloadTrait
{
    /**
     * @throws \ReflectionException
     */
    public static function fromPayload(Payload $payload): object
    {
        $reflection = new \ReflectionClass(get_called_class());
        $params = array_map(function (ReflectionParameter $parameter) {
            return $parameter->name;
        }, $reflection->getMethod('__construct')->getParameters());

        $data = [];
        foreach ($params as $param) {
            $data[$param] = $payload->get(Str::snake($param));
        }

        return $reflection->newInstance(...$data);
    }
}
