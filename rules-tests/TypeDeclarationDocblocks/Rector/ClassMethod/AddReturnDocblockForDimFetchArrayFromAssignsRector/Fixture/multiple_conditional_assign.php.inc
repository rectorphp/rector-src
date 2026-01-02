<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForDimFetchArrayFromAssignsRector\Fixture;

final class MultipleConditionalAssign
{
    public function toArray(): array
    {
        $items = [];

        if (mt_rand(0, 1)) {
            $items['key'] = 'some_string';
        }

        if (mt_rand(0, 1)) {
            $items['another_key'] = 500;
        }

        return $items;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForDimFetchArrayFromAssignsRector\Fixture;

final class MultipleConditionalAssign
{
    /**
     * @return array<string, string|int>
     */
    public function toArray(): array
    {
        $items = [];

        if (mt_rand(0, 1)) {
            $items['key'] = 'some_string';
        }

        if (mt_rand(0, 1)) {
            $items['another_key'] = 500;
        }

        return $items;
    }
}

?>
