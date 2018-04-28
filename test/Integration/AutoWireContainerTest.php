<?php

namespace Injector\Test\Integration;

use Injector\Dependency\AutoWireDependency;
use Injector\Exception\CannotResolveException;
use Injector\Exception\NotFoundException;
use Injector\Test\Stub\ClassA;
use Injector\Test\Stub\ClassAInterface;
use Injector\Test\Stub\ClassB;
use Injector\Test\Stub\ClassC;

class AutoWireContainerTest extends AbstractContainerTest
{
    public function testCanAutoWireWithoutConfiguring(): void
    {
        $dependency = $this->container->get(ClassA::class);

        $this->assertInstanceOf(ClassA::class, $dependency);
        $this->assertInstanceOf(ClassB::class, $dependency->getClassB());
    }

    public function testCanAutoWireConfiguredDependency(): void
    {
        $this->map->add(
            ClassAInterface::class,
            new AutoWireDependency(ClassA::class)
        );

        $dependency = $this->container->get(ClassAInterface::class);

        $this->assertInstanceOf(ClassA::class, $dependency);
    }

    public function testThrowsExceptionIfCannotAutoWire(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(
            'Class {Injector\Test\Stub\ClassAInterface} is not instantiable so must be bound'
        );

        $this->container->get(ClassAInterface::class);
    }

    public function testThrowsExceptionIfCannotAutoWireDueToChildDependency(): void
    {
        $this->expectException(CannotResolveException::class);
        $this->expectExceptionMessage(
            'Cannot resolve dependency {$noTypeSpecified} of {Injector\Test\Stub\ClassC} because it has no type hint'
        );

        $this->container->get(ClassC::class);
    }
}
