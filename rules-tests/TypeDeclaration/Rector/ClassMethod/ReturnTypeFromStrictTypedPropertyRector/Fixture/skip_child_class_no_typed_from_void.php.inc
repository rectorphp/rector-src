<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedPropertyRector\Fixture;

/**
 * Both class has to be in same fixture as the one that need to be skipped is the parent class
 */
class SkipChildNoTypedFromVoid
{
    public function getAge()
    {
    }
}

class SomeChildFromNoVoid extends SkipChildNoTypedFromVoid
{
    public function getAge()
    {
        echo 'some override';
    }

}
