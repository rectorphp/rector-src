<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddTypeFromResourceDocblockRector\Fixture;

final class AddReturnTypeOnlyResourceWithoutDesc
{
    /**
     * @return resource
     */
    public function getResource()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddTypeFromResourceDocblockRector\Fixture;

final class AddReturnTypeOnlyResourceWithoutDesc
{
    public function getResource(): \App\ValueObject\Resource
    {
    }
}

?>
