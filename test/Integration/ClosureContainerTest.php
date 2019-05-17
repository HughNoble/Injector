<?php

namespace Injector\Test\Integration;

use Injector\Dependency\ClosureDependency;

class ClosureContainerTest extends AbstractContainerTest
{
    public function testCanResolveClosure(): void
    {
        $bindingKey = 'binding_key';
        $expectedOutput = 'some_string';
        $closure = function () use ($expectedOutput) {
            return $expectedOutput;
        };

        $this->map->add($bindingKey, new ClosureDependency($closure));

        $this->assertEquals(
            $expectedOutput,
            $this->container->get($bindingKey)
        );
    }
}
