<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddTypeFromResourceDocblockRector\Fixture;

final class AddReturnType
{
    /**
     * @return resource|null some desc
     */
    public function getResource()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddTypeFromResourceDocblockRector\Fixture;

final class AddReturnType
{
    /**
     * @return \App\ValueObject\Resource|null some desc
     */
    public function getResource(): ?\App\ValueObject\Resource
    {
    }
}

?>
