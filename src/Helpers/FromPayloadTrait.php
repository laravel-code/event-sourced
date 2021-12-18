<?php

namespace LaravelCode\EventSouring\Helpers;

use Illuminate\Support\Str;
use LaravelCode\EventSouring\Error\ParamTypeException;
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

        $data = [];
        foreach ($reflection->getMethod('__construct')->getParameters() as $param) {
            assert($param instanceof ReflectionParameter);
            $value = $payload->get(Str::snake($param->getName()));

            $type = $param->getType();
            assert($type instanceof \ReflectionNamedType);

            if ($type->isBuiltIn()) {
                $data[$param->getName()] = $value;

                continue;
            }

            $data[$param->getName()] = self::handleToExoticParam($param, $value);
        }

        return $reflection->newInstance(...$data);
    }

    /**
     * @throws \ReflectionException
     */
    private static function handleToExoticParam(ReflectionParameter $param, int|bool|string $value): mixed
    {
        $type = $param->getType();

        assert($type instanceof \ReflectionNamedType);

        $reflection = new \ReflectionClass($type->getName());
        $name = $reflection->getShortName();
        $call = Str::camel(sprintf('to%s', $name));

        if (!is_callable([get_called_class(), $call])) {
            throw new ParamTypeException();
        }

        return call_user_func([get_called_class(), $call], $value);
    }
}
