<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class WithReturnFunctionStrict
{
    public function run()
    {
        return substr('warning', 1);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class WithReturnFunctionStrict
{
    public function run(): string
    {
        return substr('warning', 1);
    }
}

?>
