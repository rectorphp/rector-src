<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class MissingBracketOnElse
{
    public function run($value, $value2)
    {
        if ($value) {
            return 1;
        } else
            return 2;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class MissingBracketOnElse
{
    public function run($value, $value2)
    {
        if ($value) {
            return 1;
        } else {
            return 2;
        }
    }
}

?>
