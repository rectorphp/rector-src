<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Source\FirstExtension;
use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Source\SecondExtension;

final class OverrideDummyArray
{
    /**
     * @return array
     */
    public function getExtensions(): array
    {
        return [
            new FirstExtension(),
            new SecondExtension()
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Source\FirstExtension;
use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Source\SecondExtension;

final class OverrideDummyArray
{
    /**
     * @return \Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Source\Contract\ExtensionInterface[]
     */
    public function getExtensions(): array
    {
        return [
            new FirstExtension(),
            new SecondExtension()
        ];
    }
}

?>
