<?php

namespace LaravelCode\EventSourcing\Locator;

use LaravelCode\EventSourcing\Contracts\Locator\Locator;
use ReflectionException;

class InMemoryInstanceOfLocator implements Locator
{
    private array $listeners;

    private array $parentMap = [];

    public function __construct(array $listeners)
    {
        $this->listeners = $listeners;
    }

    /**
     * @throws ReflectionException
     */
    public function execute(string $eventName, array $events): mixed
    {
        foreach ($this->listeners as $listenEvent => $handler) {
            if ($this->shouldHandle($eventName, $listenEvent)) {
                return $handler;
            }
        }

        return null;
    }

    /**
     * @throws ReflectionException
     */
    private function shouldHandle(string $eventName, string $listenEvent): bool
    {
        if (!class_exists($eventName)) {
            return false;
        }

        $this->getParents($eventName, $eventName);

        return  in_array($listenEvent, $this->parentMap[$eventName] ?? []);
    }

    /**
     * @throws ReflectionException
     */
    private function getParents(string $className, string $class): void
    {
        if (isset($this->parentMap[$className])) {
            return;
        }

        $reflection = new \ReflectionClass($class);
        if ($parent = $reflection->getParentClass()) {
            $this->parentMap[$className][] = $parent->getName();
        }

        if ($interfaces = $reflection->getInterfaces()) {
            foreach ($interfaces as $interface) {
                $this->parentMap[$className][] = $interface->getName();
                $this->getParents($className, $interface->getName());
            }
        }
    }
}
