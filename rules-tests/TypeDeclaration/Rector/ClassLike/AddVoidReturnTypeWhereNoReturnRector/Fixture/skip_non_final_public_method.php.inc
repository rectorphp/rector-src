<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

class SkipNonFinalPublicMethod
{
    public function getValues()
    {
        $value = 1000;
        return;
    }
}

final class SkipNonFinalPublicMethodChild extends SkipNonFinalPublicMethod
{
    public function getValues()
    {
        return [1];
    }
}
