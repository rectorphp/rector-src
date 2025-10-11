<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\BinaryOpStandaloneAssignsToDirectRector\Fixture;

final class SkipByRefFromParamVariable
{
    public function run(&$first, &$second)
    {
        $first = 100;
        $second = 200;

        return $first <=> $second;
    }
}
