<?php

namespace Injector\Dependency;

use Closure;
use Injector\Container;

class ClosureDependency implements DependencyInterface
{
    /** @var Closure */
    private $closure;

    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    public function resolve(Container $container)
    {
        return ($this->closure)($container);
    }
}
