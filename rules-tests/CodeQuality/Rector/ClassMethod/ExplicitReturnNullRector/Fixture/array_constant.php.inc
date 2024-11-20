<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

class SkipReturnArrayConstant
{
    public static function provideClearSession()
    {
        return [
            ['Default', ['Foo' => ['foo' => 'fooValue']]],
            [null, []],
        ];
    }
}
