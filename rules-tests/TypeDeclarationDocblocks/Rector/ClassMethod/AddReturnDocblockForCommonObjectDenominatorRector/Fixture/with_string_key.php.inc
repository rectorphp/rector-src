<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Source\FirstExtension;
use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Source\SecondExtension;

final class WithStringKey
{
    public function getExtensions(): array
    {
        return [
            'a' => new FirstExtension(),
            'b' => new SecondExtension()
        ];
    }
}

?>
    -----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Source\FirstExtension;
use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Source\SecondExtension;

final class WithStringKey
{
    /**
     * @return array<string, \Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Source\Contract\ExtensionInterface>
     */
    public function getExtensions(): array
    {
        return [
            'a' => new FirstExtension(),
            'b' => new SecondExtension()
        ];
    }
}

?>
    