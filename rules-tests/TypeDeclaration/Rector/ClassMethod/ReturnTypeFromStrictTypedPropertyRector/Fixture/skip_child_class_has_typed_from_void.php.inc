<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedPropertyRector\Fixture;

/**
 * Both class has to be in same fixture as the one that need to be skipped is the parent class
 */
class SkipChildClassHasTypedFromVoid
{
    public function getAge()
    {
    }
}

class SomeChildFromVoid extends SkipChildClassHasTypedFromVoid
{
    public function getAge(): string
    {
        return 'test';
    }

}
