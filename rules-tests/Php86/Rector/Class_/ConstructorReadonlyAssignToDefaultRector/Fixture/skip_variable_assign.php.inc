<?php

namespace Rector\Tests\Php86\Rector\Class_\ConstructorReadonlyAssignToDefaultRector\Fixture;

final class SkipVariableAssign
{
    public readonly int $number;

    public function __construct(int $number)
    {
        $this->number = $number;
    }
}
