<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class ReturnScalarCast
{
    public function run($value)
    {
        return (string) $value;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class ReturnScalarCast
{
    public function run($value): string
    {
        return (string) $value;
    }
}

?>
