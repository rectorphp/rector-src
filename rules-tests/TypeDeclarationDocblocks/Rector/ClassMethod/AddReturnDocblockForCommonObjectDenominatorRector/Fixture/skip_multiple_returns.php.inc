<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Source\FirstExtension;
use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Source\SecondExtension;

final class SkipMultipleReturns
{
    public function getExtensions(): array
    {
        if (rand(0, 1)) {
            return [
                new FirstExtension(),
                new SecondExtension()
            ];
        }

        return ['string'];
    }
}
