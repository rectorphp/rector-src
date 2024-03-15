<?php

namespace Rector\Tests\Php84\Rector\Param\ExplicitNullableParamTypeRector\Fixture;

class SkipAlreadyNullable
{
    public function run(?string $a = null)
    {
    }
}
