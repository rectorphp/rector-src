<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\Fixture;

final class SkipUncheckedExceptions
{
    public function logic(): void
    {
        throw new \LogicException('programmer error');
    }

    public function subclassOfLogic(): void
    {
        throw new \InvalidArgumentException('programmer error');
    }

    public function valueError(): void
    {
        throw new \ValueError('programmer error');
    }
}
