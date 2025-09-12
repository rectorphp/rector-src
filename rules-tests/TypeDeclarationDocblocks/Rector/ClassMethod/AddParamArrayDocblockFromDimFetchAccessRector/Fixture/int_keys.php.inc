<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromDimFetchAccessRector\Fixture;

final class IntKeys
{
    public function process(array $data): void
    {
        $item = $data[55];

        $anotherItem = $data[132];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromDimFetchAccessRector\Fixture;

final class IntKeys
{
    /**
     * @param array<int, mixed> $data
     */
    public function process(array $data): void
    {
        $item = $data[55];

        $anotherItem = $data[132];
    }
}

?>
