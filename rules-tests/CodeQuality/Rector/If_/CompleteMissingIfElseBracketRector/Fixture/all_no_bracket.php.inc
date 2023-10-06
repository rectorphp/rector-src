<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class AllNoBracket
{
    public function run($value)
    {
        if ($value)
            return 1;
        elseif (rand(0, 1))
            return 2;
        else
            return 3;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class AllNoBracket
{
    public function run($value)
    {
        if ($value) {
            return 1;
        } elseif (rand(0, 1)) {
            return 2;
        } else {
            return 3;
        }
    }
}

?>