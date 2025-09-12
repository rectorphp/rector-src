<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Fixture;

final class SkipScalars
{
    public function getExtensions(): array
    {
        return [
            'two',
            'five'
        ];
    }
}

