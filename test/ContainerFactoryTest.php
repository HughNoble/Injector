<?php

namespace Injector\Test;

use Injector\Binding\BindingMap;
use Injector\Container;
use Injector\ContainerFactory;
use PHPUnit\Framework\TestCase;

class ContainerFactoryTest extends TestCase
{
    public function testItCanInstantiateContainer(): void
    {
        $container = ContainerFactory::makeContainer(
            $this->createMock(BindingMap::class)
        );

        $this->assertInstanceOf(
            Container::class,
            $container
        );
    }
}
