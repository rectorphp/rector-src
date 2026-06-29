<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\MakeInheritedMethodVisibilitySameAsParentRector\Fixture;

class PrivateClassParent extends ParentClassWithPrivateMethod
{
    public function run()
    {
    }
}

class ParentClassWithPrivateMethod
{
    private function run()
    {
    }
}
