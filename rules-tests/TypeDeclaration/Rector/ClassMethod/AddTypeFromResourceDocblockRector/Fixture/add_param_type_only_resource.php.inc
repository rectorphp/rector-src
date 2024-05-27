<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddTypeFromResourceDocblockRector\Fixture;

final class AddParamTypeOnlyResource
{
    /**
     * @param resource $resource some desc
     */
    public function setResource($resource)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddTypeFromResourceDocblockRector\Fixture;

final class AddParamTypeOnlyResource
{
    /**
     * @param \App\ValueObject\Resource $resource some desc
     */
    public function setResource(\App\ValueObject\Resource $resource)
    {
    }
}

?>
