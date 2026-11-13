<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector\Fixture;

use Closure;

final class SkipReboundClosure
{
    public function run(object $container)
    {
        (function (string $id) {
            return $this->addDefinition($id);
        })->call($container, 'test');

        $bound = (function (string $id) {
            return $this->addDefinition($id);
        })->bindTo($container, $container);

        $bound = Closure::bind(function (string $id) {
            return $this->addDefinition($id);
        }, $container, $container);
    }

    private function addDefinition(string $id): void
    {
    }
}
