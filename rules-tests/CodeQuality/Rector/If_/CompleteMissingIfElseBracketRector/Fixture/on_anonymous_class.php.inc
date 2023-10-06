<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class OnAnonymousClass
{
    public function run($value)
    {
        if ($value)
            return new class () {};
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class OnAnonymousClass
{
    public function run($value)
    {
        if ($value) {
            return new class () {};
        }
    }
}

?>
