<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source;

class SomeChild extends SomeAbstractClass
{
    public function run()
    {
    	echo 'default';
    }
}