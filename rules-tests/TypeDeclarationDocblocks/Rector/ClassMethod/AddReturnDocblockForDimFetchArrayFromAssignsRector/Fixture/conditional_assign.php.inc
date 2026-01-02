<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForDimFetchArrayFromAssignsRector\Fixture;

final class ConditionalAssign
{
    public function toArray(): array
    {
        $items = [];

        if (mt_rand(0, 1)) {
            $items['key'] = 100;
        }

        return $items;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForDimFetchArrayFromAssignsRector\Fixture;

final class ConditionalAssign
{
    /**
     * @return array<string, int>
     */
    public function toArray(): array
    {
        $items = [];

        if (mt_rand(0, 1)) {
            $items['key'] = 100;
        }

        return $items;
    }
}

?>
