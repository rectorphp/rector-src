<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source\ParentWithParams;

// ref https://3v4l.org/op5lE
class SkipChildMoreParams extends ParentWithParams{
    public function execute($foo, $bar)
    {
    }
}
