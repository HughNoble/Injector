<?php

namespace Injector\Dependency;

use Injector\Container;

class SingletonDependency implements DependencyInterface
{
    /** @var DependencyInterface */
    private $childDependency;

    /** @var mixed */
    private $cachedValue;

    public function __construct(DependencyInterface $childDependency)
    {
        $this->childDependency = $childDependency;
    }

    public function resolve(Container $container)
    {
        if (!$this->cachedValue) {
            $this->cachedValue = $this->childDependency->resolve($container);
        }

        return $this->cachedValue;
    }
}
