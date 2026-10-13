<?php

namespace Rector\Tests\CodingStyle\Rector\ClassConst\SplitGroupedClassConstantsRector\Fixture;

class AttributedConstants
{
    #[ConstantMetadata]
    public const int B = 2, C = 3;

    #[ConstantMetadata]
    private const D = 4, E = 5;
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassConst\SplitGroupedClassConstantsRector\Fixture;

class AttributedConstants
{
    #[ConstantMetadata]
    public const int B = 2;
    #[ConstantMetadata]
    public const int C = 3;

    #[ConstantMetadata]
    private const D = 4;
    #[ConstantMetadata]
    private const E = 5;
}

?>
