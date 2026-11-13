<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Fixture;

final class SkipReboundClosure
{
    public function run(object $container)
    {
        (fn (string $id, string $definition) => $this->addDefinition($id, $definition))->call($container, 'test', 'x');

        $bound = (fn (string $id, string $definition) => $this->addDefinition($id, $definition))->bindTo($container, $container);

        $bound = \Closure::bind(fn (string $id, string $definition) => $this->addDefinition($id, $definition), $container, $container);
    }

    private function addDefinition(string $id, string $definition): void
    {
    }
}
