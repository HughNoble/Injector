<?php

namespace Injector\Test\Unit;

use Injector\Binding\BindingMap;
use Injector\Binding\BindingMapInterface;
use Injector\Container;
use Injector\Dependency\DependencyFinder;
use Injector\Dependency\DependencyInterface;
use Injector\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ContainerTest extends TestCase
{
    /** @var DependencyFinder */
    private $finder;

    /** @var Container */
    private $container;

    protected function setUp(): void
    {
        $this->finder = $this->createMock(DependencyFinder::class);
        $this->container = new Container($this->finder);
    }

    public function testGet(): void
    {
        $expectedOutput = 'expected-output';

        $dependency = $this->configureFinder('TestKey');

        $dependency->expects($this->once())
            ->method('resolve')
            ->with($this->container)
            ->willReturn($expectedOutput);

        $this->assertEquals(
            $expectedOutput,
            $this->container->get('TestKey')
        );
    }

    public function testHasReturnsTrueWhenFinderDoesNotThrowException(): void
    {
        $this->configureFinder('TestKey');

        $this->assertTrue($this->container->has('TestKey'));
    }

    public function testHasReturnsFalseWhenFinderThrowsNotFoundException(): void
    {
        $this->configureFinderToThrowException('TestKey');

        $this->assertFalse($this->container->has('TestKey'));
    }

    public function testReservedBindingForContainerInterface(): void
    {
        $this->configureFinderGetToNotBeCalled();

        $this->assertTrue($this->container->has(ContainerInterface::class));
        $this->assertEquals(
            $this->container,
            $this->container->get(ContainerInterface::class)
        );
    }

    public function testReservedBindingForContainer(): void
    {
        $this->configureFinderGetToNotBeCalled();

        $this->assertTrue($this->container->has(Container::class));
        $this->assertEquals(
            $this->container,
            $this->container->get(Container::class)
        );
    }

    public function testReservedBindingForBindingMapInterface(): void
    {
        $map = $this->createMock(BindingMap::class);

        $this->configureFinderGetToNotBeCalled();
        $this->configureFinderGetMapToReturn($map);

        $this->assertTrue($this->container->has(BindingMapInterface::class));
        $this->assertEquals(
            $map,
            $this->container->get(BindingMapInterface::class)
        );
    }

    private function configureFinder(string $input): DependencyInterface
    {
        $dependency = $this->createMock(DependencyInterface::class);

        $this->finder->expects($this->once())
            ->method('get')
            ->with($input)
            ->willReturn($dependency);

        return $dependency;
    }

    private function configureFinderToThrowException($key): void
    {
        $this->finder->expects($this->once())
            ->method('get')
            ->with($key)
            ->will($this->throwException(new NotFoundException));
    }

    private function configureFinderGetToNotBeCalled(): void
    {
        $this->finder->expects($this->never())
            ->method('get');
    }

    private function configureFinderGetMapToReturn(BindingMapInterface $map): void
    {
        $this->finder->expects($this->once())
            ->method('getMap')
            ->willReturn($map);
    }
}
