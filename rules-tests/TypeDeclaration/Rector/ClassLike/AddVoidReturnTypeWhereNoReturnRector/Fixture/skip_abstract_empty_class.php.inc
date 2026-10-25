<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

abstract class SkipAbstractEmptyClass
{
    /**
     * @return string[]
     */
    public function getValues()
    {
    }
}
