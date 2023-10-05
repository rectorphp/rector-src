<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class SomeClass
{
    public function run($value)
    {
        if ($value)
            return 1;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class SomeClass
{
    public function run($value)
    {
        if ($value) {
            return 1;
        }
    }
}

?>
