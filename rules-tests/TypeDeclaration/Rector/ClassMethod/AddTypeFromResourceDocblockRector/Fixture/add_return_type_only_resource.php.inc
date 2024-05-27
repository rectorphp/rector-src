<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddTypeFromResourceDocblockRector\Fixture;

final class AddReturnTypeOnlyResource
{
    /**
     * @return resource some desc
     */
    public function getResource()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddTypeFromResourceDocblockRector\Fixture;

final class AddReturnTypeOnlyResource
{
    /**
     * @return \App\ValueObject\Resource some desc
     */
    public function getResource(): \App\ValueObject\Resource
    {
    }
}

?>
