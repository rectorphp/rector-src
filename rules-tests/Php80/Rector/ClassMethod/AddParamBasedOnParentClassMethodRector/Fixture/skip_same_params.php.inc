<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Fixture;

use Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source\ParentWithMoreParams;

class SkipSameParams extends ParentWithMoreParams{
    public function execute($foo)
    {
    }
}