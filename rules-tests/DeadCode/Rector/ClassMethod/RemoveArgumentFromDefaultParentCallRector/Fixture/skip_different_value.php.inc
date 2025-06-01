<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveDefaultArgumentFromParentCallRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveArgumentFromDefaultParentCallRector\Source\SomeParentClass;

final class SkipDifferentValue extends SomeParentClass
{
    final public function __construct(string $differentParam)
    {
        init($differentParam);

        parent::__construct(['some value']);
    }
}
