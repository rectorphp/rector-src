<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictBoolReturnExprRector\Fixture;

final class PreContentIfElseBools
{
    public function run($value)
    {
        $value += 100;

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

final class PreContentIfElseBools
{
    public function run($value): bool
    {
        $value += 100;

        if ($value) {
            return true;
        } else {
            return false;
        }
    }
}

?>
