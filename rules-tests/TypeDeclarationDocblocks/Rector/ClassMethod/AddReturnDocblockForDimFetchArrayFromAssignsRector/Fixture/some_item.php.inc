<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForDimFetchArrayFromAssignsRector\Fixture;

final class SomeItem
{
    public function toArray(): array
    {
        $items = [];
        $items['key'] = 100;

        return $items;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForDimFetchArrayFromAssignsRector\Fixture;

final class SomeItem
{
    /**
     * @return array<string, int>
     */
    public function toArray(): array
    {
        $items = [];
        $items['key'] = 100;

        return $items;
    }
}

?>
