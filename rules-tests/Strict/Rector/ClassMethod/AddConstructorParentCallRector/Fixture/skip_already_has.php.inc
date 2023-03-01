<?php

namespace Rector\Tests\DependencyInjection\Rector\ClassMethod\AddMethodParentCallRector\Fixture;

use Rector\Tests\DependencyInjection\Rector\ClassMethod\AddMethodParentCallRector\Source\ParentClassWithNewConstructor;

class SkipAlreadyHas extends ParentClassWithNewConstructor
{
    public function __construct(\stdClass $stdClass)
    {
        $value = 5;

        parent::__construct($stdClass);
    }
}
