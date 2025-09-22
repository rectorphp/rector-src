<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class SomeItemsAbove
{
    private static function getValues(): array
    {
        $list = [1, 2, 3];

        return [
            [
                'key1' => 100,
                'key2' => '-25.5%',
            ],
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class SomeItemsAbove
{
    /**
     * @return array<int, array<string, int|string>>
     */
    private static function getValues(): array
    {
        $list = [1, 2, 3];

        return [
            [
                'key1' => 100,
                'key2' => '-25.5%',
            ],
        ];
    }
}

?>
