<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Source\SomeParentWithConstructor;

final class SkipConstruct extends SomeParentWithConstructor
{
    public function __construct(int $value)
    {
        parent::__construct($value);
    }
}
