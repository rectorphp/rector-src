<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Source;

abstract class SomeParentWithConstructor
{
    public function __construct(int $value)
    {
    }
}
