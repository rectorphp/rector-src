<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector\Fixture;

final class Container9964
{
    private array $definitions = [];

    private function addDefinition(string $id, string $definition): void
    {
        $this->definitions[$id] = $definition;
    }
}

final class ContainerTest9964
{
    public function test(): void
    {
        $container = new Container9964();

        (fn (string $id, string $definition) => $this->addDefinition($id, $definition))->call(
            $container,
            'test',
            'x',
        );
    }
}
