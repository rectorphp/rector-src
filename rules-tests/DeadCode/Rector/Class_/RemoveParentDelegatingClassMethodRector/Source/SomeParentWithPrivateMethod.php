<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Source;

abstract class SomeParentWithPrivateMethod
{
    private function resolve(): void
    {
    }
}
