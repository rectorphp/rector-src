<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class CoverObjectReturn
{
    private ?\Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Source\SomeObjectInDirectArray $item = null;

    public function toArray(): array
    {
        return [
            'key1' => 100,
            'key2' => $this->item
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class CoverObjectReturn
{
    private ?\Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Source\SomeObjectInDirectArray $item = null;

    /**
     * @return array<string, int|\Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Source\SomeObjectInDirectArray|null>
     */
    public function toArray(): array
    {
        return [
            'key1' => 100,
            'key2' => $this->item
        ];
    }
}

?>
