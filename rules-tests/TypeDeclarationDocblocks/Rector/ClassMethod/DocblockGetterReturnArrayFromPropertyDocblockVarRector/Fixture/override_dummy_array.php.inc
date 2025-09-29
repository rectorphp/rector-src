<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockGetterReturnArrayFromPropertyDocblockVarRector\Fixture;

final class OverrideDummyArray
{
    /**
     * @var string[]
     */
    private array $names = [];

    /**
     * @return array
     */
    public function getNames(): array
    {
        return $this->names;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockGetterReturnArrayFromPropertyDocblockVarRector\Fixture;

final class OverrideDummyArray
{
    /**
     * @var string[]
     */
    private array $names = [];

    /**
     * @return string[]
     */
    public function getNames(): array
    {
        return $this->names;
    }
}

?>
