<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\DocblockGetterReturnArrayFromPropertyDocblockVarRector\Fixture;

final class SomePropertyWithArrayDocblock
{
    /**
     * @var string[]
     */
    private array $names = [];

    public function getNames(): array
    {
        return $this->names;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\DocblockGetterReturnArrayFromPropertyDocblockVarRector\Fixture;

final class SomePropertyWithArrayDocblock
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
