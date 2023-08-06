<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class WithJoinArgs
{
    public function run($value)
    {
        if ($value) {
            return join('', ['a', 'b']);
        }

        return 'string';
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class WithJoinArgs
{
    public function run($value): string
    {
        if ($value) {
            return join('', ['a', 'b']);
        }

        return 'string';
    }
}

?>
