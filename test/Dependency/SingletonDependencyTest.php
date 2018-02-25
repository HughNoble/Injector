<?php

namespace Injector\Test\Dependency;

use Injector\Container;
use Injector\Dependency\DependencyInterface;
use Injector\Dependency\SingletonDependency;
use PHPUnit\Framework\TestCase;

class SingletonDependencyTest extends TestCase
{
    public function testResolve(): void
    {
        $container = $this->createMock(Container::class);

        $expectedResult = 'expected-result';

        $childDependency = $this->createMock(DependencyInterface::class);
        $childDependency->expects($this->once())
            ->method('resolve')
            ->with($container)
            ->willReturn($expectedResult);

        $dependency = new SingletonDependency($childDependency);

        $this->assertEquals(
            $expectedResult,
            $dependency->resolve($container)
        );

        // Call resolve a second time to assert that the child only gets
        // resolved once and that the expected value is still returned.
        $this->assertEquals(
            $expectedResult,
            $dependency->resolve($container)
        );
    }
}
