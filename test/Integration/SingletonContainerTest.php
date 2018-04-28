<?php

namespace Injector\Test\Integration;

use Injector\Dependency\ClosureDependency;
use Injector\Dependency\SingletonDependency;

class SingletonContainerTest extends AbstractContainerTest
{
    public function testCanResolveSingleton(): void
    {
        $key = 'binding_key';
        $expectedOutput = 'expected_output';

        $closure = function () use ($expectedOutput) {
            return $expectedOutput;
        };

        $closureDependency = new ClosureDependency($closure);
        $this->map->add($key, new SingletonDependency($closureDependency));

        $output = $this->container->get($key);

        $this->assertEquals($output, $expectedOutput);
    }
}
