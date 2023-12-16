<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\FixtureHardCodedOnly;

class Float
{
    public function test()
    {
        return 10.4;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\FixtureHardCodedOnly;

class Float
{
    public function test(): float
    {
        return 10.4;
    }
}

?>
