<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

/**
 * Return with Expr even void result, converted to null, ref https://3v4l.org/vPV0Y
 */
final class SkipReturnHasExprVoid
{
    public function getValues()
    {
        return $this->someVoid();
    }

    private function someVoid(): void
    {
    }
}
