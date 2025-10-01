<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\DocblockVarArrayFromGetterReturnRector\Fixture;

final class OverrideDummyArrayVar
{
    /**
     * @var array
     */
    private array $names;

    /**
     * @return string[]
     */
    public function getNames(): array
    {
        return $this->names;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\DocblockVarArrayFromGetterReturnRector\Fixture;

final class OverrideDummyArrayVar
{
    /**
     * @var string[]
     */
    private array $names;

    /**
     * @return string[]
     */
    public function getNames(): array
    {
        return $this->names;
    }
}

?>
