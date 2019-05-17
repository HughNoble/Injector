<?php

namespace Injector\Test\Integration;

use Injector\Binding\BindingMap;
use Injector\Container;
use Injector\ContainerFactory;
use PHPUnit\Framework\TestCase;

abstract class AbstractContainerTest extends TestCase
{
    /** @var BindingMap */
    protected $map;

    /** @var Container */
    protected $container;

    protected function setUp(): void
    {
        $this->map = new BindingMap;
        $this->container = ContainerFactory::makeContainer($this->map);
    }
}
