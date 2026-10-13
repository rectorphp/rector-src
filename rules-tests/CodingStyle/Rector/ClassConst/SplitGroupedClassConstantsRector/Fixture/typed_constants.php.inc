<?php

namespace Rector\Tests\CodingStyle\Rector\ClassConst\SplitGroupedClassConstantsRector\Fixture;

class TypedConstants
{
    public const int B = 2, C = 3, D = 4;
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassConst\SplitGroupedClassConstantsRector\Fixture;

class TypedConstants
{
    public const int B = 2;
    public const int C = 3;
    public const int D = 4;
}

?>
