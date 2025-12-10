<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector\Source;

final class SomeClassWithArgs
{
    public static function foo($args)
    {
    }

    public static function optionalArgs($args = null)
    {
    }
}
