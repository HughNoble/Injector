<?php

namespace Injector\Test\Unit\Dependency;

use Injector\Binding\BindingMap;
use Injector\Dependency\AutoWireDependency;
use Injector\Dependency\DependencyFinder;
use Injector\Dependency\DependencyInterface;
use Injector\Exception\NotFoundException;
use Injector\Test\Stub\AbstractClassA;
use Injector\Test\Stub\ClassA;
use PHPUnit\Framework\TestCase;

class DependencyFinderTest extends TestCase
{
    /** @var BindingMap */
    private $map;

    /** @var DependencyFinder */
    private $finder;

    protected function setUp(): void
    {
        $this->map = $this->createMock(BindingMap::class);
        $this->finder = new DependencyFinder($this->map);
    }

    public function testGettingMap(): void
    {
        $this->assertEquals($this->map, $this->finder->getMap());
    }

    public function testItCanIdentifyMappedClass(): void
    {
        $key = 'test';
        $expectedDependency = $this->makeDependency();

        $this->configureMapToHaveDependency($key, $expectedDependency);

        $this->assertEquals(
            $expectedDependency,
            $this->finder->get($key)
        );
    }

    public function testItCanIdentifyAutoWireableClass(): void
    {
        $key = ClassA::class;

        $this->configureMapToNotHaveDependency($key);

        $dependency = $this->finder->get($key);

        $this->assertInstanceOf(
            AutoWireDependency::class,
            $dependency
        );

        $this->assertEquals($key, $dependency->getClassName());
    }

    public function testItGivesPriotityToMappedKeys(): void
    {
        $key = ClassA::class;
        $expectedDependency = $this->makeDependency();

        $this->configureMapToHaveDependency($key, $expectedDependency);

        $dependency = $this->finder->get($key);

        $this->assertEquals($expectedDependency, $dependency);
    }

    public function testItThowsExceptionIfNotMappedAndClassDoesNotExist(): void
    {
        $key = 'does-not-exist';

        $this->configureMapToNotHaveDependency($key);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(
            sprintf('Key {%s} is not bound and is not a class that exists', $key)
        );

        $this->finder->get($key);
    }

    public function testItThrowsExceptionIfNotMappedAndNotInstantiable(): void
    {
        $key = AbstractClassA::class;

        $this->configureMapToNotHaveDependency($key);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(
            sprintf('Class {%s} is not instantiable so must be bound', $key)
        );

        $this->finder->get($key);
    }

    private function makeDependency(): DependencyInterface
    {
        return $this->createMock(DependencyInterface::class);
    }

    private function configureMapToHaveDependency(
        string $key,
        DependencyInterface $dependency
    ): void {
        $this->map->expects($this->once())
            ->method('has')
            ->with($key)
            ->willReturn(true);

        $this->map->expects($this->once())
            ->method('get')
            ->with($key)
            ->willReturn($dependency);
    }

    private function configureMapToNotHaveDependency(string $key): void
    {
        $this->map->expects($this->once())
            ->method('has')
            ->with($key)
            ->willReturn(false);
    }
}
