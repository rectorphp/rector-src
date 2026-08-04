<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Source\SomeObjectWithArrayArgument;

final class SkipArrayParam
{
    public function run(SomeObjectWithArrayArgument $someObjectWithArrayArgument)
    {
        $someObjectWithArrayArgument->withArray([$this, 'name']);

        SomeObjectWithArrayArgument::staticWithArray([$this, 'name']);
    }

    public function name()
    {
    }
}
