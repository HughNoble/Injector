<?php

namespace Injector\Dependency;

use Injector\Binding\BindingMap;
use Injector\Dependency\DependencyInterface;
use Injector\Exception\NotFoundException;
use ReflectionClass;

class DependencyFinder
{
    /** @var BindingMap */
    private $map;

    public function __construct(BindingMap $map)
    {
        $this->map = $map;
    }

    public function getMap(): BindingMap
    {
        return $this->map;
    }

    public function get($key): DependencyInterface
    {
        if ($this->map->has($key)) {
            return $this->map->get($key);
        }

        if (!class_exists($key) && !interface_exists($key)) {
            throw new NotFoundException(
                sprintf('Key {%s} is not bound and is not a class that exists', $key)
            );
        }

        $reflection = new ReflectionClass($key);

        if (!$reflection->isInstantiable()) {
            throw new NotFoundException(
                sprintf('Class {%s} is not instantiable so must be bound', $key)
            );
        }

        return new AutoWireDependency($key);
    }
}
