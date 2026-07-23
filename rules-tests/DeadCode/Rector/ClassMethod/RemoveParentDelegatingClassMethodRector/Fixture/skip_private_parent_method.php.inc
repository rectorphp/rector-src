<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Source\SomeParentWithPrivateMethod;

final class SkipPrivateParentMethod extends SomeParentWithPrivateMethod
{
    protected function resolve(): void
    {
        parent::resolve();
    }
}
