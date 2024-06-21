<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

abstract class ChangeAbstractClassMethod
{
    public function run()
    {
        return new static();
    }
}

class ChangeAbstractClassMethodChild extends ChangeAbstractClassMethod
{
    public function run(): static
    {
        return new static();
    }
}
