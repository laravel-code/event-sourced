<?php

namespace LaravelCode\EventSouring;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;
use LaravelCode\EventSouring\Error\PayloadException;
use Webmozart\Assert\Assert;

class Payload
{
    private array $props = [];

    public function __construct(array $payload = [])
    {
        Assert::isArray($payload, '$payload mus be an array.');
        $this->props = $payload;

        if (!$this->has('id')) {
            $this->set('id', Str::uuid());
        }
    }

    public static function fromRequest(Request $request, string $idParamName): self
    {
        $props = [];
        $route = $request->route();

        if (get_class($route) === Route::class) {
            /** @var Route $route */
            $props = array_merge($request->all(), $route->parameters());
            $props['id'] = $route->parameter($idParamName);
        }

        return new self($props);
    }

    public function get(string $name, mixed $default = null): mixed
    {
        return $this->props[$name] ?? $default;
    }

    public function set(string $name, mixed $value): void
    {
        if (isset($this->props[$name])) {
            throw new PayloadException([$name]);
        }

        $this->props[$name] = $value;
    }

    public function has(string $name): bool
    {
        return isset($this->props[$name]);
    }
}
