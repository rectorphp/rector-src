<?php

namespace Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Source\SomeParentWithTwoParams;

final class SkipSwappedArgs extends SomeParentWithTwoParams
{
    public function run(int $first, int $second): int
    {
        return parent::run($second, $first);
    }
}
