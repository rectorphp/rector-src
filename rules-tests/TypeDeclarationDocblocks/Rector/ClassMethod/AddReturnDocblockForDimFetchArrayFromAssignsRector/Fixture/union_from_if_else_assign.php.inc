<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForDimFetchArrayFromAssignsRector\Fixture;

final class UnionFromIfElseAssign
{
    public function toArray(): array
    {
        $items = [];

        if (mt_rand(0, 1)) {
            $items = ['id' => 1];
        } else {
            $items = ['name' => 'test'];
        }

        return $items;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForDimFetchArrayFromAssignsRector\Fixture;

final class UnionFromIfElseAssign
{
    /**
     * @return array<string, int>|array<string, string>
     */
    public function toArray(): array
    {
        $items = [];

        if (mt_rand(0, 1)) {
            $items = ['id' => 1];
        } else {
            $items = ['name' => 'test'];
        }

        return $items;
    }
}

?>
