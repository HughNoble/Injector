<?php

namespace Injector;

use Injector\Binding\BindingMapInterface;
use Injector\Dependency\DependencyFinder;
use Injector\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    /** @var BindingFinder */
    private $finder;

    /** @var array */
    private $containerBindings = [
        ContainerInterface::class,
        Container::class,
    ];

    public function __construct(DependencyFinder $finder)
    {
        $this->finder = $finder;
    }

    /** @var string $key */
    public function get($key)
    {
        if ($this->isBoundToContainer($key)) {
            return $this;
        }

        if ($this->isBoundToMap($key)) {
            return $this->finder->getMap();
        }

        $dependency = $this->finder->get($key);

        return $dependency->resolve($this);
    }

    /** @var string $key */
    public function has($key): bool
    {
        if ($this->isBoundToContainer($key)) {
            return true;
        }

        if ($this->isBoundToMap($key)) {
            return true;
        }

        try {
            $this->finder->get($key);
        } catch (NotFoundException $e) {
            return false;
        }

        return true;
    }

    private function isBoundToContainer(string $key): bool
    {
        return in_array($key, $this->containerBindings);
    }

    private function isBoundToMap(string $key): bool
    {
        return $key === BindingMapInterface::class;
    }
}
