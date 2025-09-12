<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Source\FirstExtension;

final class ReturnOfObjects
{
    public function getExtensions(): array
    {
        return [
            new FirstExtension(),
            555
        ];
    }
}
