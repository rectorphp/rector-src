<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Source;

abstract class SomeParentWithTwoParams
{
    public function run(int $first, int $second): int
    {
        return $first - $second;
    }
}
