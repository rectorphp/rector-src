<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Source\AnotherExtensionWithDifferentInterface;
use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Source\FirstExtension;

final class SkipObjectsWithoutSharedParent
{
    public function getExtensions(): array
    {
        return [
            new FirstExtension(),
            new AnotherExtensionWithDifferentInterface(),
        ];
    }
}
