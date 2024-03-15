<?php

namespace Rector\Tests\Php84\Rector\Param\ExplicitNullableParamTypeRector\Fixture;

class SkipDefaultTrue
{
    public function run(bool $a = true)
    {
    }
}
