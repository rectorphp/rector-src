<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\DocblockVarFromParamDocblockInConstructorRector\Fixture;

final class OverrideDummyArrayVar
{
    /**
     * @var array
     */
    private array $names = [];

    /**
     * @param string[] $names
     */
    public function __construct(array $names)
    {
        $this->names = $names;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\DocblockVarFromParamDocblockInConstructorRector\Fixture;

final class OverrideDummyArrayVar
{
    /**
     * @var string[]
     */
    private array $names = [];

    /**
     * @param string[] $names
     */
    public function __construct(array $names)
    {
        $this->names = $names;
    }
}

?>
