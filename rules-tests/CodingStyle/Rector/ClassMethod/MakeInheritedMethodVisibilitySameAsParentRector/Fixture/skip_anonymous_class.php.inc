<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\MakeInheritedMethodVisibilitySameAsParentRector\Fixture;

use Rector\Tests\CodingStyle\Rector\ClassMethod\MakeInheritedMethodVisibilitySameAsParentRector\Source\ParentWithProtectedMethod;

class SkipAnonymousClass
{
    public function run()
    {
        $obj = new class extends ParentWithProtectedMethod {
            public function run()
            {
                return 1;
            }
        };

        var_dump($obj->run() === 1);
    }
}
