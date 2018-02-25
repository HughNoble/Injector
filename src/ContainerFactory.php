<?php

namespace Injector;

use Injector\Binding\BindingMap;
use Injector\Dependency\DependencyFinder;

class ContainerFactory
{
    public static function makeContainer(BindingMap $map): Container
    {
        return new Container(
            new DependencyFinder($map)
        );
    }
}
