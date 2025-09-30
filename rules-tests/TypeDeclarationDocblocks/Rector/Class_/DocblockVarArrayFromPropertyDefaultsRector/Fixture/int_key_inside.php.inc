<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\DocblockVarArrayFromPropertyDefaultsRector\Fixture;

final class IntKeyInside
{
    private array $array = [
        'a' => [
            'b' => [
                ['c' => 'e', 'f' => 1],
                ['d' => 2],
            ],
        ],
    ];
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\DocblockVarArrayFromPropertyDefaultsRector\Fixture;

final class IntKeyInside
{
    /**
     * @var array<string, array<string, array<int, array<string, string|int>>>>
     */
    private array $array = [
        'a' => [
            'b' => [
                ['c' => 'e', 'f' => 1],
                ['d' => 2],
            ],
        ],
    ];
}

?>