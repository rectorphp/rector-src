<?php

namespace Rector\Tests\Php86\Rector\Class_\ConstructorReadonlyAssignToDefaultRector\Fixture;

final class SkipNewObject
{
    public readonly \stdClass $value;

    public function __construct()
    {
        $this->value = new \stdClass();
    }
}
