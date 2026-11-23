<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

trait SkipTraitPublicMethod
{
    public function getValues()
    {
        $value = 1000;
        return;
    }
}
