<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowBoolDocblockReturnTypeRector\Fixture;

/**
 * @return bool|string[]
 */
function narrowFunctionReturn(): false|array
{
    return false;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowBoolDocblockReturnTypeRector\Fixture;

/**
 * @return false|string[]
 */
function narrowFunctionReturn(): false|array
{
    return false;
}

?>
