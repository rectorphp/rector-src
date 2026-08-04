<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Source\SomeObjectWithArrayArgument;

final class SkipUnionArrayParam
{
    public function run()
    {
        return new SomeObjectWithArrayArgument([$this, 'name']);
    }

    public function name()
    {
    }
}
