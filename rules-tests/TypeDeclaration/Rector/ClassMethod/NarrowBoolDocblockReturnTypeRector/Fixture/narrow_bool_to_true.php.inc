<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowBoolDocblockReturnTypeRector\Fixture;

class NarrowBoolToTrue
{
    /**
     * @return bool|int[]
     */
    public function run(): true|array
    {
        return true;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowBoolDocblockReturnTypeRector\Fixture;

class NarrowBoolToTrue
{
    /**
     * @return true|int[]
     */
    public function run(): true|array
    {
        return true;
    }
}

?>
