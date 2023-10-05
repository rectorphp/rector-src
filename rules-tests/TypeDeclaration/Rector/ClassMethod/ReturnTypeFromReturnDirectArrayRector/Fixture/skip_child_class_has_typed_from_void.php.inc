<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnDirectArrayRector\Fixture;

/**
 * Both class has to be in same fixture as the one that need to be skipped is the parent class
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
