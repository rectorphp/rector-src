<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\MakeInheritedMethodVisibilitySameAsParentRector\Source;

class ParentWithProtectedMethod
{
    protected function run()
    {
    }
}
