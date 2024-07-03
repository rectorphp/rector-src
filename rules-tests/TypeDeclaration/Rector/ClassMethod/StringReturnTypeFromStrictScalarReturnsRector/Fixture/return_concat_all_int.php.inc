<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class ReturnConcatAllInt
{
    public function run()
    {
        return 1 . 1;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class ReturnConcatAllInt
{
    public function run(): string
    {
        return 1 . 1;
    }
}

?>
