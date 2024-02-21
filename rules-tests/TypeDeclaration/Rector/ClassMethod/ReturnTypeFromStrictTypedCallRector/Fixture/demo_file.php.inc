<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class DemoFile
{
    
    function aa($param)
    {
        return abs($param);
    }
}
?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class DemoFile
{
    
    function aa($param): int|float
    {
        return abs($param);
    }
}
?>
