<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class MissingBracketOnElseIf
{
    public function run($value, $value2)
    {
        if ($value) {
            return 1;
        } elseif ($value2)
            return 2;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class MissingBracketOnElseIf
{
    public function run($value, $value2)
    {
        if ($value) {
            return 1;
        } elseif ($value2) {
            return 2;
        }
    }
}

?>
