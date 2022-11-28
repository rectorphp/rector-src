<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictBoolReturnExprRector\Fixture;

class SkipChildClassHasTyped
{
    public function run()
    {
        return $this->first() && true;
    }

    public function first()
    {
        return true;
    }
}

class SomeChild extends SkipChildClassHasTyped
{
    public function run(): string
    {
        return 'test';
    }

}