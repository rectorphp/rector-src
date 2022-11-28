<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

use stdClass;

class SkipChildClassHasTyped
{
    public function action()
    {
        return new stdClass();
    }
}

class SomeChild extends SkipChildClassHasTyped
{
    public function run(): bool
    {
        return true;
    }

}