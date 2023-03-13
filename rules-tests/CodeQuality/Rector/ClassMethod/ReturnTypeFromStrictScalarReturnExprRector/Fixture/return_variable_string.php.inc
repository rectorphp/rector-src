<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class ReturnVariableString
{
    public function run($value)
    {
        $result = $value . '/' ;
        return $result;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class ReturnVariableString
{
    public function run($value): string
    {
        $result = $value . '/' ;
        return $result;
    }
}

?>
