<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class ReturnConcatMultiple
{
    public function run($value, $bar, $baz)
    {
        return $value . '/' . $bar . $baz;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class ReturnConcatMultiple
{
    public function run($value, $bar, $baz): string
    {
        return $value . '/' . $bar . $baz;
    }
}

?>
