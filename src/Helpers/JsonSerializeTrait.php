<?php

namespace LaravelCode\EventSouring\Helpers;

use Illuminate\Support\Str;
use ReflectionProperty;

trait JsonSerializeTrait
{
    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $toExtract = get_object_vars($this);
        foreach ($toExtract as $property => $value) {
            $snakeCase = Str::snake($property);
            if ($property !== $snakeCase) {
                $toExtract[$snakeCase] = $value;
                unset($toExtract[$property]);
            }
        }

        $reflection = new \ReflectionClass(get_called_class());
        $properties = collect($reflection->getProperties())
            ->filter(function (ReflectionProperty $property) {
                return $property->isPublic();
            })->map(function (ReflectionProperty $property) {
                return Str::snake($property->getName());
            });

        extract($toExtract);

        return compact(...$properties);
    }
}
