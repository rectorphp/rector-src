<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

abstract class SkipChangeAbstractClassMethod
{
    public function run()
    {
        return new static();
    }
}

class SkipChangeAbstractClassMethodChild extends SkipChangeAbstractClassMethod
{
    public function run(): static
    {
        return new static();
    }
}
