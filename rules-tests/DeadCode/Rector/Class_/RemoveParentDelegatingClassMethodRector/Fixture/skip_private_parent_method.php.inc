<?php

namespace Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Source\SomeParentWithPrivateMethod;

final class SkipPrivateParentMethod extends SomeParentWithPrivateMethod
{
    protected function resolve(): void
    {
        parent::resolve();
    }
}
