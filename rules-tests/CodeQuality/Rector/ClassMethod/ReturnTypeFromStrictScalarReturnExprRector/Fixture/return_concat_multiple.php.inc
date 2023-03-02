<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class ReturnConcatMultiple
{
    public function run($value, $bar, $baz, $bat)
    {
        return $value . '/' . $bar . $baz . $bat;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\Fixture;

final class ReturnConcatMultiple
{
    public function run($value, $bar, $baz, $bat): string
    {
        return $value . '/' . $bar . $baz . $bat;
    }
}

?>
