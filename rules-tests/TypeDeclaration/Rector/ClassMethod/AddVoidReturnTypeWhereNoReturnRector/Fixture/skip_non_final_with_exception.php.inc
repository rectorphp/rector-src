<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

use Rector\Exception\ShouldNotHappenException;

class SkipNonFinalWithException
{
    public function getValues()
    {
        throw new ShouldNotHappenException('Implement in child');
    }
}
