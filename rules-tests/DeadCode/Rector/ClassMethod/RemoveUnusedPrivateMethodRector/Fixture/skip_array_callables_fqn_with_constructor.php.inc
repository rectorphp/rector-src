<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector\Fixture;

final class SkipArrayCallablesFqnWithConstuctor
{
    private $args;

    public function __construct(...$args)
    {
        $this->args = $args;
    }

    public function run()
    {
        $array  = [3, 2, 1];

        usort($array, [SkipArrayCallablesFqnWithConstuctor::class, 'sort']);

        return $array;
    }

    private function sort($a, $b)
    {
        return $a <=> $b;
    }
}
