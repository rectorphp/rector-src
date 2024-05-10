<?php

namespace Rector\Tests\CodeQuality\Rector\Expression\TernaryFalseExpressionToIfRector\Fixture;

final class IncludeNoSideEffectExpr
{
    public function run($value, $someMethod)
    {
        $value ? $someMethod->call($value) : true;
    }

    public function run2($value, $someMethod, $variable)
    {
        $value ? $someMethod->call($value) : $variable;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Expression\TernaryFalseExpressionToIfRector\Fixture;

final class IncludeNoSideEffectExpr
{
    public function run($value, $someMethod)
    {
        if ($value) {
            $someMethod->call($value);
        }
    }

    public function run2($value, $someMethod, $variable)
    {
        if ($value) {
            $someMethod->call($value);
        }
    }
}

?>
