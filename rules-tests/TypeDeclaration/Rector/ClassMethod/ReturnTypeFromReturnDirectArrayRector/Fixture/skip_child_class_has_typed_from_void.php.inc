<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnDirectArrayRector\Fixture;

/**
 * Both classes have to be in the same fixture, as the one that needs to be skipped is the parent class
 */
class SkipChildClassHasTypedFromVoid
{
    public function get()
    {
    }
}

class SomeChildFromVoid extends SkipChildClassHasTypedFromVoid
{
    public function get(): array
    {
        return [1];
    }

}
