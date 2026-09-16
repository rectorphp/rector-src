<?php

namespace Rector\Tests\Php86\Rector\Class_\ConstructorReadonlyAssignToDefaultRector\Fixture;

final class SkipNotReadonly
{
    public int $number;

    public function __construct()
    {
        $this->number = 100;
    }
}
