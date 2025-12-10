<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\BinaryOpStandaloneAssignsToDirectRector\Fixture;

use Rector\Tests\CodingStyle\Rector\ClassMethod\BinaryOpStandaloneAssignsToDirectRector\Source\SomeGetter;

final class SkipAnyCallLikeWithArgs
{
    public function run(SomeGetter $firstSomeGetter, SomeGetter $secondSomeGetter)
    {
        $first = $firstSomeGetter::getSome($arg, $arg2, $arg3, $arg4, $arg5, $arg6);
        $second = $secondSomeGetter::getSome($arg, $arg2, $arg3, $arg4, $arg5, $arg6);

        return $first <=> $second;
    }
}
