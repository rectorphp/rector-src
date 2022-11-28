<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedPropertyRector\Fixture;

class SkipChildClassHasTyped
{
    private int $age = 100;

    public function getAge()
    {
        return $this->age;
    }
}

class SomeChild extends SkipChildClassHasTyped
{
    public function getAge(): string
    {
        return 'test';
    }

}