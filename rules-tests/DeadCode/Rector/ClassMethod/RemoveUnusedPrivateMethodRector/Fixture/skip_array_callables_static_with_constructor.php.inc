<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Fixture;

final class SkipArrayCallablesStaticWithConstructor
{
    private $args;

    public function __construct(...$args)
    {
        $this->args = $args;
    }

    public function run()
    {
        $array  = [3, 2, 1];

        usort($array, [static::class, 'sort']);

        return $array;
    }

    private function sort($a, $b)
    {
        return $a <=> $b;
    }
}
