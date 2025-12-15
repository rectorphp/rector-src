<?php

namespace Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture;

use Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture\ReplaceMethodArgumentWithConstant;

class SkipNamedArguments
{
    public function run()
    {
        $obj = new ReplaceMethodArgumentWithConstant();
        $obj->setSomeMethod(someValue: 'some value');
    }
}
