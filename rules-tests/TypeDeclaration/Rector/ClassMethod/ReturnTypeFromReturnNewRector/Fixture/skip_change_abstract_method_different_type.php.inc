<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

abstract class SkipChangeAbstractMethodDifferentType
{
    public function run()
    {
        return new static();
    }
}

class SkipChangeAbstractMethodDifferentTypeChild extends SkipChangeAbstractMethodDifferentType
{
    public function run(): string
    {
        return 'a';
    }
}
