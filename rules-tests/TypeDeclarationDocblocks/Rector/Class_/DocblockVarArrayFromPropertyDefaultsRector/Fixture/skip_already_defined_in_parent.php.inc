<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\DocblockVarArrayFromPropertyDefaultsRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\DocblockVarArrayFromPropertyDefaultsRector\Source\BaseWithPropertyDocblock;

final class SkipAlreadyDefinedInParent extends BaseWithPropertyDocblock
{
    public array $myArray = ['d', 'e', 'f'];
}

?>
