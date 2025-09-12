<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromDimFetchAccessRector\Fixture;

final class ParamArray
{
    public function process(array $data): void
    {
        $item = $data['key'];

        $anotherItem = $data['another_key'];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromDimFetchAccessRector\Fixture;

final class ParamArray
{
    /**
     * @param array<string, mixed> $data
     */
    public function process(array $data): void
    {
        $item = $data['key'];

        $anotherItem = $data['another_key'];
    }
}

?>
