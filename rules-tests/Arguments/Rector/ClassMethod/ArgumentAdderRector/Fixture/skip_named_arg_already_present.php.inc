<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeMultiArg;

class SkipNamedArgAlreadyPresent
{
    public function run()
    {
        $obj = new SomeMultiArg();
        // Parameter 'c' is already provided as named argument
        $obj->run(b: 5, c: 999);
        $obj->run(c: 999, b: 5);
    }
}
