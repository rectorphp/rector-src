<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromDimFetchAccessRector\Fixture;

final class OverrideDummyArrayParam
{
    /**
     * @param array $data
     */
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

final class OverrideDummyArrayParam
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
