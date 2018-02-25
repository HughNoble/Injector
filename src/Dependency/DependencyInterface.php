<?php

namespace Injector\Dependency;

use Injector\Container;

interface DependencyInterface
{
    public function resolve(Container $container);
}
