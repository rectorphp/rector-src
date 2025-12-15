<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeMultiArg;

class SkipNamedArguments
{
    public function run()
    {
        $obj = new SomeMultiArg();
        $obj->run(b: 5);
        $obj->run(c: 4);
        $obj->run(0, 1, c: 6);
    }
}

