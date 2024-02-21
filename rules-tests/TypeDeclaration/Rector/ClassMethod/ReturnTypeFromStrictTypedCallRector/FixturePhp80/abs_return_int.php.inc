<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class AbsReturnInt
{
    function aa(int $param)
    {
        return abs($param);
    }
}
?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class AbsReturnInt
{
    function aa(int $param): int
    {
        return abs($param);
    }
}
?>
