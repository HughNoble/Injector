<?php

namespace Injector\Test\Unit\Binding;

use Injector\Binding\BindingMap;
use Injector\Dependency\DependencyInterface;
use Injector\Exception\NotMappedException;
use PHPUnit\Framework\TestCase;

class BindingMapTest extends TestCase
{
    /** @var BindingMap */
    private $map;

    protected function setUp()
    {
        $this->map = new BindingMap;
    }

    public function testHasWhenNotMapped(): void
    {
        $this->assertFalse($this->map->has('not-mapped'));
    }

    public function testHasWhenMapped(): void
    {
        $key = 'test';
        $dependency = $this->getDependency();

        $this->map->add($key, $dependency);

        $this->assertTrue($this->map->has($key));
    }

    public function testGetWhenMapped(): void
    {
        $key = 'test';
        $dependency = $this->getDependency();

        $this->map->add($key, $dependency);

        $this->assertEquals(
            $dependency,
            $this->map->get($key)
        );
    }

    public function testGetThrowsExceptionWhenNotMapped()
    {
        $this->expectException(NotMappedException::class);
        $this->expectExceptionMessage('Binding {not-mapped} has not been mapped');

        $this->map->get('not-mapped');
    }

    private function getDependency(): DependencyInterface
    {
        return $this->createMock(DependencyInterface::class);
    }
}
