<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowBoolDocblockReturnTypeRector\Fixture;

class NarrowBoolToFalse
{
    /**
     * @return bool|string[]
     */
    public function run(): false|array
    {
        return false;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowBoolDocblockReturnTypeRector\Fixture;

class NarrowBoolToFalse
{
    /**
     * @return false|string[]
     */
    public function run(): false|array
    {
        return false;
    }
}

?>
