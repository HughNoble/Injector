<?php

namespace Injector\Test\Unit\Dependency;

use Injector\Container;
use Injector\Dependency\AutoWireDependency;
use Injector\Dependency\DependencyInterface;
use Injector\Exception\CannotResolveException;
use Injector\Test\Stub\AbstractClassA;
use Injector\Test\Stub\ClassA;
use Injector\Test\Stub\ClassB;
use Injector\Test\Stub\ClassC;
use PHPUnit\Framework\TestCase;

class AutoWireDependencyTest extends TestCase
{
    public function testGettingClassName(): void
    {
        $this->assertEquals(
            'TestClass',
            (new AutoWireDependency('TestClass'))->getClassName()
        );
    }

    public function testItCanResolveAClassWithDependency(): void
    {
        $dependency = new AutoWireDependency(ClassA::class);

        $classB = new ClassB;

        $class = $dependency->resolve(
            $this->getContainerFor(ClassB::class, $classB)
        );

        $this->assertInstanceOf(ClassA::class, $class);

        $this->assertInstanceOf(ClassB::class, $class->getClassB());
    }

    public function testItCanResolveAClassWithNoConstructor(): void
    {
        $dependency = new AutoWireDependency(ClassB::class);

        $class = $dependency->resolve(
            $this->createMock(Container::class)
        );

        $this->assertInstanceOf(ClassB::class, $class);
    }

    public function testItThrowsExceptionIfTryingToResolveANotInstantiableClass(): void
    {
        $dependency = new AutoWireDependency(AbstractClassA::class);

        $this->expectException(CannotResolveException::class);
        $this->expectExceptionMessage(
            sprintf('Cannot resolve {%s} because it is not instantiable', AbstractClassA::class)
        );

        $dependency->resolve(
            $this->createMock(Container::class)
        );
    }

    public function testItThrowsExceptionIfTryingToResolveAClassWithNoTypeHint(): void
    {
        $dependency = new AutoWireDependency(ClassC::class);

        $this->expectException(CannotResolveException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Cannot resolve dependency {$noTypeSpecified} of {%s} because it has no type hint',
                ClassC::class
            )
        );

        $dependency->resolve(
            $this->createMock(Container::class)
        );
    }

    private function getContainerFor($key, $value): Container
    {
        $container = $this->createMock(Container::class);

        $container->expects($this->once())
            ->method('get')
            ->with($key)
            ->willReturn($value);

        return $container;
    }
}
