<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class OnMethodCall
{
    public function run($value)
    {
        if ($value)
            return $this->execute();
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class OnMethodCall
{
    public function run($value)
    {
        if ($value) {
            return $this->execute();
        }
    }
}

?>
