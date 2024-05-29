<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddTypeFromResourceDocblockRector\Fixture;

final class AddReturnTypeWithoutDesc
{
    /**
     * @return resource|null
     */
    public function getResource()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddTypeFromResourceDocblockRector\Fixture;

final class AddReturnTypeWithoutDesc
{
    public function getResource(): ?\App\ValueObject\Resource
    {
    }
}

?>
