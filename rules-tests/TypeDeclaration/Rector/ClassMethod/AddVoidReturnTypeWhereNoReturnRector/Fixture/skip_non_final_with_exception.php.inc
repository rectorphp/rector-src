<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

use Rector\Core\Exception\ShouldNotHappenException;

class SkipNonFinalWithException
{
    public function getValues()
    {
        throw new ShouldNotHappenException('Implement in child');
    }
}
