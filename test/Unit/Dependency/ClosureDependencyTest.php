<?php

namespace Injector\Test\Unit\Dependency;

use Injector\Container;
use Injector\Dependency\ClosureDependency;
use PHPUnit\Framework\TestCase;

class ClosureDependencyTest extends TestCase
{
    public function testResolve(): void
    {
        $container = $this->createMock(Container::class);

        $dependency = new ClosureDependency(function ($input) use ($container) {
            $this->assertEquals($container, $input);

            return 'expected-output';
        });

        $this->assertEquals(
            'expected-output',
            $dependency->resolve($container)
        );
    }
}
