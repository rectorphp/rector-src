<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictBoolReturnExprRector\Fixture;

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

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictBoolReturnExprRector\Fixture;

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
