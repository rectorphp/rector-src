<?php

namespace Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\Source;

class FooBar
{
    public static function foo($args)
    {
    }

    public static function optionalArgs($args = null)
    {
    }
}
