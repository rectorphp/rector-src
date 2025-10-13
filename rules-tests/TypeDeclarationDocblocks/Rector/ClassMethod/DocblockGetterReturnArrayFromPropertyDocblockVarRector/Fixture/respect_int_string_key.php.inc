<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockGetterReturnArrayFromPropertyDocblockVarRector\Fixture;

final class RespectIntStringKey
{
    /**
     * @var array<int, string>
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

final class RespectIntStringKey
{
    /**
     * @var array<int, string>
     */
    private array $names = [];

    /**
     * @return array<int, string>
     */
    public function getNames(): array
    {
        return $this->names;
    }
}

?>
