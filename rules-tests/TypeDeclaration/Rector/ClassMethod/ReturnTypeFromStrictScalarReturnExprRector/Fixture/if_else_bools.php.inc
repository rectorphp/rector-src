<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class IfElseBools
{
    public function run($value)
    {
        if ($value) {
            return true;
        } else {
            return false;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class IfElseBools
{
    public function run($value): bool
    {
        if ($value) {
            return true;
        } else {
            return false;
        }
    }
}

?>
