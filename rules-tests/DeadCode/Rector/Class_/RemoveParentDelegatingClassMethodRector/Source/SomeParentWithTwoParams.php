<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Source;

abstract class SomeParentWithTwoParams
{
    public function run(int $first, int $second): int
    {
        return $first - $second;
    }
}
