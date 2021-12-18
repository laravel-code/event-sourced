<?php

namespace LaravelCode\EventSouring\Helpers;

use Illuminate\Support\Str;
use LaravelCode\EventSouring\Error\ParamTypeException;
use ReflectionParameter;
use ReflectionProperty;

trait JsonSerializeTrait
{
    /**
     * @return array
     * @throws \ReflectionException
     */
    public function jsonSerialize(): array
    {
        $reflection = new \ReflectionClass(get_called_class());
        $toExtract = $this->getParams($reflection->getMethod('__construct')->getParameters());

        /*
         * Get all other params that are not set in the constructor
         */
        foreach (get_object_vars($this) as $property => $value) {
            $snakeCase = Str::snake($property);

            if (!isset($toExtract[$snakeCase])) {
                $toExtract[$snakeCase] = $value;
            }
        }

        /**
         * Only export public params.
         */
        $properties = collect($reflection->getProperties())
            ->filter(function (ReflectionProperty $property) {
                return $property->isPublic();
            })->map(function (ReflectionProperty $property) {
                return Str::snake($property->getName());
            });

        extract($toExtract);

        return compact(...$properties);
    }

    /**
     * @param ReflectionParameter[] $params
     * @return array
     */
    private function getParams(array $params): array
    {
        return collect($params)->map(function ($param) {
            assert($param instanceof ReflectionParameter);

            if (!isset($this->{$param->getName()})) {
                return null;
            }

            $type = $param->getType();
            assert($type instanceof \ReflectionNamedType);
            if ($type->isBuiltin()) {
                return ['name' => $param->getName(), 'value' => $this->{$param->getName()}];
            }

            return ['name' => $param->getName(), 'value' => $this->handleFromExoticParam($param, $this->{$param->getName()})];
        })
            ->filter()
            ->reduce(function ($carry, $item) {
                $snakeCase = Str::snake($item['name']);
                $carry[$snakeCase] = $item['value'];

                return $carry;
            }, []);
    }

    /**
     * @throws \ReflectionException
     */
    public function handleFromExoticParam(ReflectionParameter $param, mixed $value): int|string|bool
    {
        $type = $param->getType();
        assert($type instanceof \ReflectionNamedType);

        $reflection = new \ReflectionClass($type->getName());
        $name = $reflection->getShortName();
        $call = Str::camel(sprintf('from%s', $name));

        if (!is_callable([get_called_class(), $call])) {
            throw new ParamTypeException();
        }

        return call_user_func([get_called_class(), $call], $value);
    }
}
