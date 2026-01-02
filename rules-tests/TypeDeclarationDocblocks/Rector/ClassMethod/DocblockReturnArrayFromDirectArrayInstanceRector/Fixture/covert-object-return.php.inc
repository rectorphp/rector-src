<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class CoverObjectReturn
{
    private ?\Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Sourde\SomeObjectInDirectArray $item = null;

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
    private ?\Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Sourde\SomeObjectInDirectArray $item = null;

    /**
     * @return array<string, \Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Sourde\SomeObjectInDirectArray|int|null>
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
