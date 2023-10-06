<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class OnClosure
{
    public function run($value)
    {
        if ($value)
            return function () {};
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class OnClosure
{
    public function run($value)
    {
        if ($value) {
            return function () {};
        }
    }
}

?>
