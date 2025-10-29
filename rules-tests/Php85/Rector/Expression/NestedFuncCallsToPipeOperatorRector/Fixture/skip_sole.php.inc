<?php

namespace Rector\Tests\Php85\Rector\Expression\NestedFuncCallsToPipeOperatorRector\Fixture;

final class SkipSole
{
    public function run()
    {
        $result = trim('  Hello World!  ');
    }
}
