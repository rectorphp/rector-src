<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveDefaultArgumentFromParentCallRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveArgumentFromDefaultParentCallRector\Source\SomeParentClass;

final class SkipDynamicFromParam extends SomeParentClass
{
    final public function __construct(string $differentParam, array $params = [])
    {
        init($differentParam);

        parent::__construct($params);
    }
}
