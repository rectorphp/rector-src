<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class SkipReAssignWithAssignOp
{

    public function __construct(
        private int $value = 0
    ) {
    }

    public function add(int $flag): void
    {
        $this->value |= $flag;
    }

}
