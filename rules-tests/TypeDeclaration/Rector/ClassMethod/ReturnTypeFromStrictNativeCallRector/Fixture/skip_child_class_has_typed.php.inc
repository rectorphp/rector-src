<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector\Fixture;

class SkipChildClassHasTyped
{
    public function run()
    {
        return strlen('value');
    }
}

class SomeChild extends SkipChildClassHasTyped
{
    public function run(): string
    {
        return 'test';
    }

}