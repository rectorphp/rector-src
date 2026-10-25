<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

class SkipNonFinalProtectedMethod
{
    protected function getValues()
    {
    }
}
