<?php

namespace Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Source\SomeParentWithConstructor;

final class SkipConstruct extends SomeParentWithConstructor
{
    public function __construct(int $value)
    {
        parent::__construct($value);
    }
}
