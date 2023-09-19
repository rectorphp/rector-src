<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

function result(): string
{
    return '';
}

final class ReturnNonNativeFunction
{
    public function run()
    {
        return result();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

function result(): string
{
    return '';
}

final class ReturnNonNativeFunction
{
    public function run(): string
    {
        return result();
    }
}

?>
