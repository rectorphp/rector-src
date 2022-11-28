<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictConstantReturnRector\Fixture;

class SkipChildClassHasTyped
{
    public const NAME = 'name';

    public function run()
    {
        return self::NAME;
    }
}

class SomeChild extends SkipChildClassHasTyped
{
    public function run(): bool
    {
        return true;
    }

}