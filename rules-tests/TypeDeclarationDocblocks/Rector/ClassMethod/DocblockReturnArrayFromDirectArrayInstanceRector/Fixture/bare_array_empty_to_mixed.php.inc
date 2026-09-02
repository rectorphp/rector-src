<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

class BareArrayEmptyToMixed
{
    /**
     * @return array
     */
    public function getRefreshTokenKeys()
    {
        return [];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

class BareArrayEmptyToMixed
{
    /**
     * @return mixed[]
     */
    public function getRefreshTokenKeys()
    {
        return [];
    }
}

?>
