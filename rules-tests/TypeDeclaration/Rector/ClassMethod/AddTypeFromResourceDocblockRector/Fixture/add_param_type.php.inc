<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddTypeFromResourceDocblockRector\Fixture;

final class AddParamType
{
    /**
     * @param resource|null $resource some desc
     */
    public function setResource($resource)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddTypeFromResourceDocblockRector\Fixture;

final class AddParamType
{
    /**
     * @param \App\ValueObject\Resource|null $resource some desc
     */
    public function setResource(?\App\ValueObject\Resource $resource)
    {
    }
}

?>
