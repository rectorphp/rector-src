<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class CoverImplicitKey
{
    private static function getExpectedAllOwners(): array
    {
        return [
            [
                'key1' => 100,
                'key2' => '-25.5%',
            ],
            [
                'key3' => 'Yes',
                'key4' => 200,
            ],
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class CoverImplicitKey
{
    /**
     * @return array<int, array<string, int|string>>
     */
    private static function getExpectedAllOwners(): array
    {
        return [
            [
                'key1' => 100,
                'key2' => '-25.5%',
            ],
            [
                'key3' => 'Yes',
                'key4' => 200,
            ],
        ];
    }
}

?>
