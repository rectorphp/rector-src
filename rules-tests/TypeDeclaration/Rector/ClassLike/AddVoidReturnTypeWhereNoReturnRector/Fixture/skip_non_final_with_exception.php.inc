<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

use Rector\Exception\ShouldNotHappenException;

class SkipNonFinalWithException
{
    public function getValues()
    {
        throw new ShouldNotHappenException('Implement in child');
    }
}
