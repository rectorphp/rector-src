<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddTypeFromResourceDocblockRector\Fixture;

final class AddParamTypeWithoutDesc
{
    /**
     * @param resource|null $resource
     */
    public function setResource($resource)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddTypeFromResourceDocblockRector\Fixture;

final class AddParamTypeWithoutDesc
{
    public function setResource(?\App\ValueObject\Resource $resource)
    {
    }
}

?>
