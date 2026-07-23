<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Source\SomeParentWithTwoParams;

final class SkipSwappedArgs extends SomeParentWithTwoParams
{
    public function run(int $first, int $second): int
    {
        return parent::run($second, $first);
    }
}
