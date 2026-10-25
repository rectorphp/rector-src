<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

use Iterator;

final class SkipExistingType
{
    public function getValues(): Iterator
    {
        yield [100];
    }
}
