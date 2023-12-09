<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

abstract class SkipAbstractMethod
{
    public function run();
}

class SkipAbstractMethodChild extends SkipAbstractMethod
{
    public function run(): static
    {
        return new static();
    }
}
