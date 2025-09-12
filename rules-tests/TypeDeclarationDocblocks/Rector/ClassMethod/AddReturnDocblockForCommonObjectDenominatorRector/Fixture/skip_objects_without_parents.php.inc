<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Source\FirstOrphan;
use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Source\SecondOrphan;

final class SkipObjectsWithoutParents
{
    public function getExtensions(): array
    {
        return [
            new FirstOrphan(),
            new SecondOrphan()
        ];
    }
}
