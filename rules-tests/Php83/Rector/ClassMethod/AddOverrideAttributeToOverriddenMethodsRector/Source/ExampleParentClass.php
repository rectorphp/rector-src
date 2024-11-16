<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source;

class ExampleParentClass
{
    public function foo()
    {
        $value = 'non empty';
    }

    private function bar()
    {
        $value = 'non empty';
    }
}
