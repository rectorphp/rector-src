<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Fixture;

final class UniqueTypeSingle
{
    public function getExtensions(): array
    {
        return [
            new \DateTime('now'),
            new \DateTime('tomorrow'),
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\Fixture;

final class UniqueTypeSingle
{
    /**
     * @return \DateTime[]
     */
    public function getExtensions(): array
    {
        return [
            new \DateTime('now'),
            new \DateTime('tomorrow'),
        ];
    }
}

?>
