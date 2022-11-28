<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

class SkipChildClassHasTyped
{
    public function run()
    {
        $values = [];

        return $values;
    }
}

class SomeChild extends SkipChildClassHasTyped
{
    public function run(): string
    {
        return 'test';
    }

}