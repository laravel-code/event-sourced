<?php

namespace LaravelCode\EventSouring;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        $props = array_merge($request->all(), $request->route()->parameters());
        $props['id'] = $request->route()->parameter($idParamName);

        return new self($props);
    }

    public function get(string $name, $default = null)
    {
        return $this->props[$name] ?? $default;
    }

    public function set(string $name, $value)
    {
        if (isset($this->props[$name])) {
            throw new \Exception('Cannot set existing value');
        }

        $this->props[$name] = $value;
    }

    public function has($name)
    {
        return isset($this->props[$name]);
    }
}
