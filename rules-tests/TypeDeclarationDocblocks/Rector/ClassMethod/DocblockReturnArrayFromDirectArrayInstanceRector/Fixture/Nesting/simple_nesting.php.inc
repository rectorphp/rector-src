<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture\Nesting;

final class SimpleNesting
{
    public static function returnArray(): array
    {
        return [
            'key1' => 'string-1',
            'key2' => [
                'key2a' => null,
                'key2b' => [
                    'key2b1' => 'ABC',
                ],
            ],
            'key3' => 'string-2',
            'key4' => null,
            'key5' => 'string-3',
        ];
    }
}
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture\Nesting;

final class SimpleNesting
{
    /**
     * @return array<string, array<string, array<string, string>|null>|string|null>
     */
    public static function returnArray(): array
    {
        return [
            'key1' => 'string-1',
            'key2' => [
                'key2a' => null,
                'key2b' => [
                    'key2b1' => 'ABC',
                ],
            ],
            'key3' => 'string-2',
            'key4' => null,
            'key5' => 'string-3',
        ];
    }
}
