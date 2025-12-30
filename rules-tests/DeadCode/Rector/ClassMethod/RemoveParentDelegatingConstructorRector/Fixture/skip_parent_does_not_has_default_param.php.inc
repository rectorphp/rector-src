<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Source\SomeParentWithoutDefaultParam;

final class SkipParentDoesNotHasDefaultParam extends SomeParentWithoutDefaultParam
{
    public function __construct(array $value = [])
    {
        parent::__construct($value);
    }
}
