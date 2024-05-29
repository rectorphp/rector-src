<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddTypeFromResourceDocblockRector\Fixture;

final class AddParamTypeOnlyResourceWithoutDesc
{
    /**
     * @param resource $resource
     */
    public function setResource($resource)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddTypeFromResourceDocblockRector\Fixture;

final class AddParamTypeOnlyResourceWithoutDesc
{
    public function setResource(\App\ValueObject\Resource $resource)
    {
    }
}

?>
