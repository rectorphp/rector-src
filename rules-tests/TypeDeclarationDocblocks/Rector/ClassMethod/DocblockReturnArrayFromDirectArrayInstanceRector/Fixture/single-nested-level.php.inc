<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class SingleNestedLevel
{
    public function getNames($value): array
    {
        return [
            'key' => [
                'subkey' => $value
            ],
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class SingleNestedLevel
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public function getNames($value): array
    {
        return [
            'key' => [
                'subkey' => $value
            ],
        ];
    }
}

?>
