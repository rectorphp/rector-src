<?php

namespace Rector\Tests\DeadCode\Rector\Closure\RemoveUnusedClosureVariableUseRector\Fixture;

class Fixture
{
    public function run($value)
    {
        return function () use ($value) {
            return 'value';
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Closure\RemoveUnusedClosureVariableUseRector\Fixture;

class Fixture
{
    public function run($value)
    {
        return function () {
            return 'value';
        };
    }
}

?>
