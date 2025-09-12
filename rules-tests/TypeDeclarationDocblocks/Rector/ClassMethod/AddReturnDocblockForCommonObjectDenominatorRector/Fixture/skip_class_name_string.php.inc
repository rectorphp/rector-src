<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Fixture;

use PhpParser\Node\Stmt\Class_;

final class SkipClassNameString
{
    public function run(): array
    {
        return [
            Class_::class,
        ];
    }
}
