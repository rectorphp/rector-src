<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\MakeInheritedMethodVisibilitySameAsParentRector\Fixture;

use Rector\Tests\CodingStyle\Rector\ClassMethod\MakeInheritedMethodVisibilitySameAsParentRector\Source\ParentWithProtectedMethod;
use Rector\Tests\CodingStyle\Rector\ClassMethod\MakeInheritedMethodVisibilitySameAsParentRector\Source\SomeInterface;

final class SkipImplementsInterface extends ParentWithProtectedMethod implements SomeInterface
{
    public function run()
    {
    }
}
