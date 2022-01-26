<?php

namespace LaravelCode\EventSourcing\Helpers;

use Illuminate\Support\Str;
use LaravelCode\EventSourcing\Error\ParamTypeException;
use LaravelCode\EventSourcing\Payload;
use ReflectionParameter;

trait FromPayloadTrait
{
    /**
     * @throws \ReflectionException
     */
    public static function fromPayload(Payload $payload): self
    {
        $reflection = new \ReflectionClass(get_called_class());

        $data = [];
        foreach ($reflection->getMethod('__construct')->getParameters() as $param) {
            assert($param instanceof ReflectionParameter);
            $value = $payload->get(Str::snake($param->getName()), $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null);

            $type = $param->getType();
            assert($type instanceof \ReflectionNamedType);

            if ($type->isBuiltIn()) {
                $data[$param->getName()] = $value;

                continue;
            }

            $data[$param->getName()] = self::handleToExoticParam($param, $value);
        }
        /** @var self $instance */
        $instance = $reflection->newInstance(...$data);

        return $instance;
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
