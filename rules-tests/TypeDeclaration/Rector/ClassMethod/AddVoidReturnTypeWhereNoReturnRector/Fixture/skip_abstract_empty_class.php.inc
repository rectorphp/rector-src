<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

abstract class SkipAbstractEmptyClass
{
    /**
     * @return string[]
     */
    public function getValues()
    {
    }
}
