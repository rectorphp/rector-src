<?php

namespace Rector\Tests\Php86\Rector\Class_\ConstructorReadonlyAssignToDefaultRector\Fixture;

final class SkipConditionalAssign
{
    public readonly int $number;

    public function __construct(bool $flag)
    {
        if ($flag) {
            $this->number = 100;
        } else {
            $this->number = 200;
        }
    }
}
