<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedPropertyRector\Fixture;

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