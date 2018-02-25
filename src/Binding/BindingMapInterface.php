<?php

namespace Injector\Binding;

use Injector\Dependency\DependencyInterface;

interface BindingMapInterface
{
    public function add(string $key, DependencyInterface $value): void;

    public function has(string $key): bool;

    public function get(string $key): DependencyInterface;
}
