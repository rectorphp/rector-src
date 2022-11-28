<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

class SkipChildClassHasTyped
{
    public function getData()
    {
        return $this->getNumber();
    }

    private function getNumber(): int
    {
        return 1000;
    }
}

class SomeChild extends SkipChildClassHasTyped
{
    public function getData(): string
    {
        return 'test';
    }

}