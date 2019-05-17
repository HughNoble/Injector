<?php

namespace Injector\Dependency;

use Injector\Container;
use Injector\Exception\CannotResolveException;
use ReflectionClass;
use ReflectionParameter;

class AutoWireDependency implements DependencyInterface
{
    /** @var string */
    private $className;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function resolve(Container $container)
    {
        $reflection = new ReflectionClass($this->getClassName());

        if (!$reflection->isInstantiable()) {
            throw new CannotResolveException(
                sprintf(
                    'Cannot resolve {%s} because it is not instantiable',
                    $this->getClassName()
                )
            );
        }

        return $reflection->newInstanceArgs(
            $this->getDependencies($container, $reflection)
        );
    }

    private function getDependencies(
        Container $container,
        ReflectionClass $reflection
    ): array {
        $dependencies = [];

        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return [];
        }

        foreach ($constructor->getParameters() as $parameter) {
            $dependencies[] = $this->getDependency($container, $parameter);
        }

        return $dependencies;
    }

    public function getDependency(
        Container $container,
        ReflectionParameter $parameter
    ) {
        $class = $parameter->getClass();

        if (!$class) {
            throw new CannotResolveException(
                sprintf(
                    'Cannot resolve dependency {$%s} of {%s} because it has no type hint',
                    $parameter->getName(),
                    $this->getClassName()
                )
            );
        }

        return $container->get($class->name);
    }
}
