<?php

namespace Rector\Tests\Php84\Rector\Param\ExplicitNullableParamTypeRector\Fixture;

class SkipMixed
{
    public function run(mixed $a = null)
    {
    }
}
