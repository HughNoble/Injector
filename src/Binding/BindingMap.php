<?php

namespace Injector\Binding;

use Injector\Dependency\DependencyInterface;
use Injector\Exception\NotMappedException;

class BindingMap implements BindingMapInterface
{
    private $map;

    public function __construct()
    {
        $this->map = [];
    }

    public function add(string $key, DependencyInterface $value): void
    {
        $this->map[$key] = $value;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->map);
    }

    public function get(string $key): DependencyInterface
    {
        if (!$this->has($key)) {
            throw new NotMappedException(
                sprintf('Binding {%s} has not been mapped', $key)
            );
        }

        return $this->map[$key];
    }
}
