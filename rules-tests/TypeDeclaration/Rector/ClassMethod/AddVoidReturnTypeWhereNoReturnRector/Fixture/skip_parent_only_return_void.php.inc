<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

class SkipParentOnlyReturnVoid
{
    protected function getValues()
    {
    }
}

final class SomeChildReturnParentVoid extends SkipParentOnlyReturnVoid
{
    protected function getValues()
    {
        return [];
    }
}
