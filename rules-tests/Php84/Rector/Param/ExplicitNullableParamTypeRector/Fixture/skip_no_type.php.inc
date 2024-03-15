<?php

namespace Rector\Tests\Php84\Rector\Param\ExplicitNullableParamTypeRector\Fixture;

class SkipNoType
{
    public function run($a = null)
    {
    }
}
