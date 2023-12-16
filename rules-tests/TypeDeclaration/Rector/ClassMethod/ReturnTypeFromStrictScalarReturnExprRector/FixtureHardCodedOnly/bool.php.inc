<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\FixtureHardCodedOnly;

class Bool
{
    public function test($value)
    {
        if ($value) {
            return false;
        }

        return true;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\FixtureHardCodedOnly;

class Bool
{
    public function test($value): bool
    {
        if ($value) {
            return false;
        }

        return true;
    }
}

?>
