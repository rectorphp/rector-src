<?php

namespace Rector\Tests\Php84\Rector\Param\ExplicitNullableParamTypeRector\Fixture;

class SkipNullParam
{
    public function run(null $a = null)
    {
    }
}
